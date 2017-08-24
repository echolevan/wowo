<?php

namespace App\Http\Controllers;

use App\Download;
use App\Order;
use App\Plug;
use App\Recharge;
use App\Score;
use App\Tag;
use App\Thumb;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Levan\Baidu\Stat\BaiduStatFacade;
use function Sodium\increment;

class PlugController extends Controller
{
    public function index (Request $request, $type)
    {
        $type = config('my.plug_type')[$type];

        if ($request->orderBy == 1) {
            $orderBy = 'created_at';
        } else if ($request->orderBy == 2) {
            $orderBy = 'download_num';
        } else {
            $orderBy = 'rank';
        }

        // search 条件
        $tag_active = isset($request->tag_active) ? $request->tag_active : 0;
        $tag_active_pid = isset($request->tag_active_pid) ? $request->tag_active_pid : 0;
        $limit = isset($request->page) ? ($request->page - 1) * 10 : 0;
        $keyWord = isset($request->keyword) ? $request->keyword : '';

        if ($tag_active_pid == 0) {
            // 查询type_one
            $where = "type_one = " . $tag_active;
        } else {
            $where = "type_two = " . $tag_active;
        }

        $plugs = Plug::where('type', $type)
            ->with('thumbs')
            ->when($tag_active != 0 || $tag_active_pid != 0, function ($query) use ($where, $tag_active, $tag_active_pid) {
                return $query->whereRaw($where);
            })
            ->when($keyWord != '', function ($query) use ($keyWord) {
                return $query->where('title', 'like', "%$keyWord%");
            })
            ->where('is_new',1)
            ->where([['status', 1], ['is_check', 1]])
            ->skip($limit)->take(10)

            ->orderBy($orderBy, 'desc')
            ->get();

        $count = Plug::where('type', $type)
            ->when($tag_active != 0 || $tag_active_pid != 0, function ($query) use ($where, $tag_active, $tag_active_pid) {
                return $query->whereRaw($where);
            })
            ->when($keyWord != '', function ($query) use ($keyWord) {
                return $query->where('title', 'like', "%$keyWord%");
            })
            ->where('is_new',1)
            ->where([['status', 1], ['is_check', 1]])
            ->count();

        return ['plugs' => $plugs, 'count' => $count];
    }


    /**
     * @param $type
     * @return array
     */
    public function plugRank ($type)
    {
        $type = $type == 'undefined' ? $type = 0 : config('my.plug_type')[$type];
        $rank_download = Plug::when($type > 0, function ($query) use ($type) {
            $query->where('type', $type);
        })->where([['status', 1], ['is_check', 1]])->skip(0)->take(5)->where('is_new',1)->orderBy('download_num', 'desc')->get();

        $rank_score = Plug::when($type > 0, function ($query) use ($type) {
            $query->where('type', $type);
        })->where([['status', 1], ['is_check', 1]])->skip(0)->take(5)->where('is_new',1)->orderBy('rank', 'desc')->get();

        return ['rank_download' => $rank_download, 'rank_score' => $rank_score];
    }

    public function plugInfo ($id)
    {

        $plug = Plug::with('thumbs')
            ->with('tag_one')->with('tag_two')->with('user')
            ->with(['historys' => function ($query) {
                $query->select('id','plug_id','version','game_version','created_at')->latest();
            }])->with(['is_pay' => function ($query) {
                $query->where('orders.user_id', Auth::id());
            }])->where('id',$id)->first();
        $thumb_list = [];
        foreach ($plug->thumbs as $k => $v) {
            $thumb_list[$k]['src'] = $v->thumb;
            $thumb_list[$k]['w'] = $v->width;
            $thumb_list[$k]['h'] = $v->height;
        }

        $plug->like_plug = DB::table('like_plugs')->where('user_id',Auth::id())->where('plug_id',$plug->plug_id)->count();
        $plug->collect_plug = DB::table('collect_plugs')->where('user_id',Auth::id())->where('plug_id',$plug->plug_id)->count();

        // 更新说明
        $updated_info = Plug::where('plug_id', $plug->plug_id)->select('updated_info', 'created_at')->latest()->get();

        return ['plug' => $plug, 'thumb_list' => $thumb_list, 'updated_info' => $updated_info];
    }

    public function update_plugInfo ($id)
    {
        $plug = Plug::with('thumbs')
            ->with('tag_one')->with('tag_two')->with('user')->with(['like_plug' => function ($query) {
                $query->where('users.id', Auth::id());
            }])->with(['collect_plug' => function ($query) {
                $query->where('users.id', Auth::id());
            }])->with(['is_pay' => function ($query) {
                $query->where('orders.user_id', Auth::id());
            }])->find($id);

        if ($plug->user_id !== Auth::id()) {
            return ['sta' => 0];
        }

        $plug->is_free = $plug->is_free === 0 ? false : true;
        $plug->type = [$plug->type, $plug->type_one, $plug->type_two];
        return ['plug' => $plug];
    }

    /**
     * @param $id
     * 收藏id
     */
    public function collect_this ($id)
    {
        $user = User::find(Auth::id());

        $collect_plug = DB::table('collect_plugs')->where('user_id',Auth::id())->where('plug_id',$id)->count();

        if ($collect_plug > 0) {
            return ['sta' => 0, 'msg' => '你已经收藏过了'];
        }
        DB::beginTransaction();
        try {
            $user->collect_plug()->attach($id);
            $collect = Plug::where('plug_id',$id)->increment('like_num');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '收藏失败'];
        }


        if ($collect) {
            return ['sta' => 1, 'msg' => '收藏成功'];
        }
        return ['sta' => 0, 'msg' => '收藏失败'];

    }

    /**
     * @param $id
     * @return array
     * 点赞
     */
    public function like_this ($id)
    {
        $user = User::find(Auth::id());

        $like_plug = DB::table('like_plugs')->where('user_id',Auth::id())->where('plug_id',$id)->count();

        if ($like_plug > 0) {
            return ['sta' => 0, 'msg' => '你已经点赞过了'];
        }

        DB::beginTransaction();
        try {
            $user->like_plug()->attach($id);
            $collect = Plug::where('plug_id',$id)->increment('collect_num');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '点赞失败'];
        }


        if ($collect) {
            return ['sta' => 1, 'msg' => '点赞成功'];
        }
        return ['sta' => 0, 'msg' => '点赞失败'];
    }


    /**
     * @param $id
     * 下载插件
     */
    public function download ($id)
    {
        $plug = Plug::select('type', 'is_free', 'title', 'content', 'wwb', 'plug_id')->find($id);
        // 是否是免费的
        if ($plug->is_free == 0) {
            Plug::where('plug_id',$plug->plug_id)->increment('download_num');
            // 是否是免费的
            $this->download_num($plug->plug_id);
            if ($plug->type == 1 || $plug->type == 2) {
                // wa twm  model
                return ['sta' => 1, 'type' => 1, 'info' => $plug];
            } else {
                // download
                return ['sta' => 1, 'type' => 2, 'info' => $plug];
            }

        } else {
            // 查看 orders 表是否存在 该订单 存在则不再收费 用唯一ID 去查询
            if (Order::where([['plug_only_id', $plug->plug_id], ['user_id', Auth::id()]])->count() > 0) {
                // 曾经购买过
                Plug::where('plug_id',$plug->plug_id)->increment('download_num');
                $this->download_num($plug->plug_id);
                if ($plug->type == 1 || $plug->type == 2) {
                    // wa twm  model
                    return ['sta' => 1, 'type' => 1, 'info' => $plug];
                } else {
                    // download
                    return ['sta' => 1, 'type' => 2, 'info' => $plug];
                }
            }
            return ['sta' => 1, 'type' => 3, 'info' => $plug];
        }
    }

    public function download_num($plug_id)
    {
        $download = Download::where('plug_id',$plug_id)->where('mouth',date('Y-m',time()))->first();
        if(!$download){
            Download::create([
               'plug_id' => $plug_id,
               'mouth' => date('Y-m',time()),
               'num' => 1,
            ]);
        }else{
            $download->increment('num');
        }

        return true;
    }

    public function to_pay (Request $request)
    {
        if (!Auth::check()) {
            return ['sta' => 0, 'msg' => '请先登录'];
        }

        $plug_id = $request->id;
        if (!$plug_id) {
            return ['sta' => 0, 'msg' => '插件ID不存在'];
        }

        $plug = Plug::where('id', $plug_id)->first();
        if (!$plug) {
            return ['sta' => 0, 'msg' => '插件不存在'];
        }

        //check user wwb
        $user = Auth::user();
        if ($user->wwb < $plug->wwb) {
            return ['sta' => 0, 'msg' => '窝窝币不足，请先充值'];
        }

        //pay
        DB::beginTransaction();
        try {
            Order::create([
                'plug_id' => $plug->id,
                'plug_only_id' => $plug->plug_id,
                'user_id' => $user->id,
                'wwb' => $plug->wwb,
                'status' => 1,
                'type' => $plug->type
            ]);

            User::where('id', $user->id)->update([
                'wwb' => $user->wwb - $plug->wwb
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '支付失败'];
        }

        return ['sta' => 1, 'msg' => '支付成功'];

    }

    /**
     * @return array
     * 得到分类连动
     */
    public function plug_all_info ()
    {
        $tag = [[1, 1, 'WA'], [1,2,'TMW'] , [2, 3, '插件']];

        $res = [];
        foreach ($tag as $k => $v) {
            $res[$k]['value'] = $v[1]; // type 1 WA 2 TMW 3 插件
            $res[$k]['label'] = $v[2];
            $res[$k]['children'] = Tag::with(['children' => function ($query) {
                $query->select(DB::raw('tags.id as value , tags.name as label ,  tags.pid , tags.id'));
            }])->select(DB::raw('tags.id as value , tags.name as label , tags.pid , tags.id'))->where('type', $v[0])->where('pid', 0)->where([['status', 1], ['is_check', 1]])
                ->when(Auth::user()->is_admin === 0 , function ($query){
                    $query->where('is_for_user', 1);
                })
                ->get();
        }

        return $res;
    }

    public function plug_all_info_for_admin()
    {
        $tag = [[1, 1, 'WA/TMW'], [2, 3, '插件']];

        $res = [];
        foreach ($tag as $k => $v) {
            $res[$k]['value'] = $v[1]; // type 1 WA 2 TMW 3 插件
            $res[$k]['label'] = $v[2];
            $res[$k]['children'] = Tag::with(['children' => function ($query) {
                $query->select(DB::raw('tags.id as value , tags.name as label ,  tags.pid , tags.id'));
            }])->select(DB::raw('tags.id as value , tags.name as label , tags.pid , tags.id'))->where('type', $v[0])->where('pid', 0)->where([['status', 1], ['is_check', 1]])->get();
        }

        return $res;
    }

    public function plug_all_info_no_login()
    {
        $tag = [[1, 1, 'WA'] , [1,2,'TMW']];

        $res = [];
        foreach ($tag as $k => $v) {
            $res[$k]['value'] = $v[1]; // type 1 WA 2 TMW 3 插件
            $res[$k]['label'] = $v[2];
            $res[$k]['children'] = Tag::with(['children' => function ($query) {
                $query->select(DB::raw('tags.id as value , tags.name as label ,  tags.pid , tags.id'));
            }])->select(DB::raw('tags.id as value , tags.name as label , tags.pid , tags.id'))->where('type', $v[0])->where('pid', 0)->where([['status', 1], ['is_check', 1], ['is_for_user', 1]])->get();
        }

        return $res;
    }
    /**
     * @param Request $request
     * @return array
     * 上传插件
     */
    public function upload_plug (Request $request, $plug_id = 0)
    {
        $req = collect($request->data);
        $plug = $req->except('uploadList', 'type', 'content', 'plug_url');
        $type = $req->only('type');
        $uploadList = $req->only('uploadList');

        // 开始写入值
        $Plug = new Plug;
        foreach ($plug as $k => $v) {
            $Plug->$k = $v;
        }
        $Plug->user_id = Auth::id();
        $Plug->plug_id = ($plug_id === 0 || $plug_id === 'undefined') ? get_only_one_plug_id() : $plug_id;
        $Plug->wwb = is_null($req['wwb']) ? 0 : $req['wwb'];
        $Plug->type = $type['type'][0];
        $Plug->type_one = $type['type'][1];
        $Plug->version = date('Ymd');
        $Plug->type_two = isset($type['type'][2]) ? $type['type'][2] : 0;
        $Plug->content = $type['type'][0] === 1 || $type['type'][0] === 2 ? $req['content'] : $req['plug_url'];  // 分字符串 跟下载链接
        DB::beginTransaction();
        try {
            if ($plug_id !== 0 && $plug_id !== 'undefined') {
                // 升级插件 历史插件 is_new  = 0
                Plug::where('plug_id', $plug_id)->update(['is_new' => 0]);
                $num = Plug::where('plug_id', $plug_id)->select('download_num','like_num','collect_num','score','version')->first();
                if($Plug->version === $num->version){
                    $Plug->version = $Plug->version ."_".date('His');
                }
                $Plug->download_num = $num->download_num;
                $Plug->like_num = $num->like_num;
                $Plug->collect_num = $num->collect_num;
                $Plug->score = $num->score;
            }
            $Plug->save();
            foreach ($uploadList['uploadList'] as $v) {
                Thumb::create([
                    'thumb' => $v['url'],
                    'plug_id' => $Plug->id,
                    'width' => $v['width'],
                    'height' => $v['height'],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ['sta' => 0, 'msg' => '上传失败'];
        }

        return ['sta' => 1, 'msg' => '上传成功'];
    }

    public function update_plug (Request $request, $id)
    {
        $req = collect($request->data);
        $plug = $req->except('uploadList', 'type', 'content', 'plug_url');
        $type = $req->only('type');
        $uploadList = $req->only('uploadList');
        // 开始写入值
        $Plug = Plug::find($id);

        if ($Plug->user_id !== Auth::id()) {
            return ['sta' => 0];
        }

        foreach ($plug as $k => $v) {
            $Plug->$k = $v;
        }
        $Plug->user_id = Auth::id();
        $Plug->wwb = is_null($req['wwb']) ? 0 : $req['wwb'];
        $Plug->type = $type['type'][0];
        $Plug->type_one = $type['type'][1];
        $Plug->type_two = isset($type['type'][2]) ? $type['type'][2] : 0;
        $Plug->content = $type['type'][0] === 1 || $type['type'][0] === 2 ? $req['content'] : $req['plug_url'];  // 分字符串 跟下载链接
        DB::beginTransaction();
        try {
            $Plug->save();
            //删除之前的图片
            Thumb::where('plug_id', $id)->delete();
            foreach ($uploadList['uploadList'] as $v) {
                Thumb::create([
                    'thumb' => $v['url'],
                    'plug_id' => $id,
                    'width' => $v['width'],
                    'height' => $v['height'],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ['sta' => 0, 'msg' => '编辑失败'];
        }

        return ['sta' => 1, 'msg' => '编辑成功'];
    }


    public function check_plug_id ($id)
    {
        $plug = Plug::where('plug_id', $id)->first();

        if ($plug && $plug->user_id === Auth::id())
            return ['sta' => 1];
        return ['sta' => 0];
    }

    public function check_version(Request $request , $plug_id = 0)
    {
        if(count(explode(".",$request->version)) === 2 && strlen($request->version) === 3 && explode(".",$request->version)[1] >= '0'){

            if($plug_id !== 0 && $plug_id !== 'undefined'){
                // update
                $plug = Plug::where('plug_id',$plug_id)->where('is_new',1)->first();
                if($request->version <= $plug->version ){
                    return ['sta'=>0 , 'msg'=>'版本号要大于上一个版本'];
                }else{
                    return ['sta'=>1];
                }

            }else{
                return ['sta'=>1];
            }

        }else{
            return ['sta'=>0 , 'msg'=>'版本号格式为1.0 1.1 2.0这类的'];
        }
    }


    public function rate_score(Request $request , $plug_id)
    {
        $is_rate = Score::where('user_id',Auth::id())->where('plug_id',$plug_id)->count();

        if ($is_rate > 0) {
            return ['sta' => 0, 'msg' => '你已经评分过了'];
        }

        $sum_count = Score::where('plug_id',$plug_id)->select(DB::raw('sum(score) as score , count(*) as count'))->first();

        DB::beginTransaction();
        try {
            Score::create([
                'user_id' => Auth::id(),
                'plug_id' => $plug_id,
                'score' => $request->score * 2,
            ]);

            Plug::where('plug_id',$plug_id)->update([
                'score' => floor(($sum_count->score + $request->score *2 ) / ($sum_count->count + 1))
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '评分失败'];
        }

        return ['sta' => 1, 'msg' => '评分成功' ];
    }

    public function plug_index()
    {

        if(Cache::has('plug_index_wa')){
            $wa = Cache::get('plug_index_wa');
        }else{
            $wa = Plug::where('is_new',1)->where('type',1)->skip(0)->take(20)->select('id','title','created_at')->where([['status', 1], ['is_check', 1]])->latest()->get();
            Cache::put('plug_index_wa',$wa,60);
        }

        if(Cache::has('plug_index_twm')){
            $twm = Cache::get('plug_index_twm');
        }else{
            $twm = Plug::where('is_new',1)->where('type',2)->skip(0)->take(20)->select('id','title','created_at')->where([['status', 1], ['is_check', 1]])->latest()->get();
            Cache::put('plug_index_twm',$twm,60);
        }

        if(Cache::has('plug_index_plug')){
            $plug = Cache::get('plug_index_plug');
        }else{
            $plug = Plug::where('is_new',1)->where('type',3)->skip(0)->take(20)->select('id','title','created_at')->where([['status', 1], ['is_check', 1]])->latest()->get();
            Cache::put('plug_index_plug',$plug,60);
        }

        if(Cache::has('plug_index_new_user')){
            $user = Cache::get('plug_index_new_user');
        }else{
            $user = User::latest()->value('name');
            Cache::put('plug_index_new_user',$user,60);
        }

        if(Cache::has('plug_index_recent_plugs')){
            $recent_plugs = Cache::get('plug_index_recent_plugs');
        }else{
            $recent_plugs = Plug::where('is_new',1)->skip(0)->take(20)->select('id','title','created_at')->where([['status', 1], ['is_check', 1]])->latest()->get();
            Cache::put('plug_index_recent_plugs',$recent_plugs,60);
        }

        if(Cache::has('plug_index_total_person')){
            $total_person = Cache::get('plug_index_total_person');
        }else{
             try{
                 $total_person = BaiduStatFacade::getData(date('Ymd'),date('Ymd'));
                 $total_person = isset($total_person['info']['body']['data'][0]['result']['sum']['0'][1]);
             }catch(\Exception $e){
                 $total_person = 0;
             }
            Cache::put('plug_index_total_person',$total_person,60);
        }

        if(Cache::has('plug_index_download_plugs')){
            $download_plugs = Cache::get('plug_index_download_plugs');
        }else{
            $download_plugs = Plug::where('is_new',1)->skip(0)->take(20)->select('id','title','created_at')->orderBy('download_num')->where([['status', 1], ['is_check', 1]])->get();
            Cache::put('plug_index_download_plugs',$download_plugs,60);
        }

        if(Cache::has('plug_index_download_plugs_this_mouth')){
            $download_plugs_this_mouth = Cache::get('plug_index_download_plugs_this_mouth');
        }else{
            $download_plugs_this_mouth = Plug::where('is_new',1)
                ->where([['status', 1], ['is_check', 1]])
                ->skip(0)->take(20)->select('plugs.id','plugs.title','plugs.created_at' , 'downloads.num')->
            leftJoin('downloads', 'plugs.plug_id' ,'=' ,'downloads.plug_id')

                ->orderBy('downloads.num','desc')
                ->get();
            Cache::put('plug_index_download_plugs_this_mouth',$download_plugs_this_mouth,60);
        }

        if(Cache::has('plug_index_census')){
            $census = Cache::get('plug_index_census');
        }else{
            $census['plugs_count'] = Plug::where([['status', 1], ['is_check', 1]])->count();
            $census['was_count'] = Plug::where([['status', 1], ['is_check', 1]])->where('type',1)->count();
            $census['tmws_count'] = Plug::where([['status', 1], ['is_check', 1]])->where('type',2)->count();
            $census['today_count'] = Plug::where([['status', 1], ['is_check', 1]])->whereRaw('TO_DAYS( NOW( ) ) - TO_DAYS( created_at ) <= 1')->count();
            $census['last_time'] = Plug::where([['status', 1], ['is_check', 1]])->orderBy('created_at','desc')->value('created_at');
            if($census['last_time']){
                $census['last_time'] = $census['last_time']->toDateTimeString();
            }else{
                $census['last_time'] = '暂无';
            }
            $census['user_count'] = User::count();
            $census['lm_count'] = User::where('camp',1)->count();
            $census['bl_count'] = User::where('camp',2)->count();
            Cache::put('plug_index_census',$census,60);
        }
        return ['was'=>$wa,'twms'=>$twm,'plugs'=>$plug,'recent_plugs'=>$recent_plugs,'download_plugs'=>$download_plugs,'new_user'=>$user,'download_plugs_this_mouth'=>$download_plugs_this_mouth,'census'=>$census ,'total_person'=>$total_person];
    }

    public function plug_list(Request $request , $page , $size)
    {
        $where = Plug::when($request->search['name'] != null, function ($query) use ($request) {
            return $query->where('plugs.title', 'like' , '%'.$request->search['name'].'%');
        })
            ->when($request->search['plug_id'] != null, function ($query) use ($request) {
                return $query->where('plugs.plug_id', $request->search['plug_id']);
            })
            ->when($request->search['user_id'] != null, function ($query) use ($request) {
                return $query->where('plugs.user_id', $request->search['user_id']);
            })
            ->when($request->search['tagType'] != null, function ($query) use ($request) {
                return $query->where('plugs.type', $request->search['tagType']);
            })
            ->when($request->search['user_name'] != null, function ($query) use ($request) {
                return $query->join('users' , 'users.id', '=' ,'plugs.user_id')->where('users.name', 'like' , '%'.$request->search['user_name'].'%')->select('users.id','users.name');
            })
            ->when($request->search['wwb'] == '1', function ($query) use ($request) {
                return $query->where('plugs.wwb',0);
            })
            ->when($request->search['wwb'] == '2', function ($query) use ($request) {
                return $query->where('plugs.wwb', '>' ,0);
            })
            ->when($request->search['status'] != null, function ($query) use ($request) {
                return $query->where('plugs.status', $request->search['status']);
            })
            ->when($request->search['is_check'] != null, function ($query) use ($request) {
                return $query->where('plugs.is_check', $request->search['is_check']);
            })
            ->orderBy('is_new','desc')
            ->when($request->search['orderBySome'] != null, function ($query) use ($request) {
                return $query->orderBy("plugs.".$request->search['orderBySome'], $request->search['orderByF']);
            })
            ->when($request->search['is_new'] != 0, function ($query) use ($request) {
                return $query->where("plugs.is_new", 1);
            });

        $count = $where->count();
        $list = $where->with(['thumbs','tag_one','tag_two','user'])->skip(($page-1)*$size)->take($size)->select('plugs.*')->get();
        return ['sta'=>1, 'count'=>$count, 'list'=>$list];
    }

    public function change_rank($id, $rank)
    {
        $plug = Plug::where('id',$id)->update([
           'rank' => $rank
        ]);
        if($plug)
            return ['sta'=>1,'msg'=>'编辑成功'];
        return ['sta'=>0,'msg'=>'编辑失败'];
    }


    public function change_status($id, $v)
    {
        $tag = Plug::where('id',$id)->update([
            'status' =>$v
        ]);
        if($tag)
            return ['sta'=>1, 'msg'=>'更新成功'];
        return ['sta'=>0, 'msg'=>'更新失败'];
    }

    public function change_is_check($id, $v)
    {
        $tag = Plug::where('id',$id)->update([
            'is_check' =>$v
        ]);
        if($tag)
            return ['sta'=>1, 'msg'=>'更新成功'];
        return ['sta'=>0, 'msg'=>'更新失败'];
    }
}
