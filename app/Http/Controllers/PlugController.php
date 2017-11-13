<?php

namespace App\Http\Controllers;

use App\Download;
use App\Order;
use App\Plug;
use App\PlugDel;
use App\Recharge;
use App\Score;
use App\Tag;
use App\Thumb;
use App\Tool;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            $orderBy = 'like_num';
        }

        // search 条件
        $tag_active = isset($request->tag_active) ? $request->tag_active : 0;
        $tag_active_pid = isset($request->tag_active_pid) ? $request->tag_active_pid : 0;
        $limit = isset($request->page) ? ($request->page - 1) * 10 : 0;
        $keyWord = isset($request->keyword) ? $request->keyword : '';
        $serBy = isset($request->serBy) ? $request->serBy : '';

        if ($tag_active_pid == 0) {
            // 查询type_one
            $where = "type_one = " . $tag_active;
        } else {
            $where = "type_two = " . $tag_active . " or (type = {$type} and type_one = {$tag_active_pid} and type_two = 0)";
        }

        $plugs = Plug::where('type', $type)
            ->with('thumbs')

            ->when($keyWord != '', function ($query) use ($keyWord) {
                return $query->where('title', 'like', "%$keyWord%");
            })
            ->when($serBy != '', function ($query) use ($serBy) {
                return $query->where('game_version', $serBy);
            })
            ->with(['is_pay' => function ($query) {
                $query->where('orders.user_id', Auth::id());
            }])
            ->where('is_new',1)
            ->where([['status', 1], ['is_check', 1]])
            ->when($tag_active != 0 || $tag_active_pid != 0, function ($query) use ($where, $tag_active, $tag_active_pid) {
                return $query->whereRaw($where);
            })
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

        // get game_version
        $game_version = Tool::where('name','game_version')->orderBy('value','desc')->pluck('value');
        return ['plugs' => $plugs, 'count' => $count, 'game_version'=>$game_version, 'today_time'=>Date('Y-m-d')];
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
        })->where([['status', 1], ['is_check', 1]])->skip(0)->take(5)->where('is_new',1)->orderBy('like_num', 'desc')->get();

        return ['rank_download' => $rank_download, 'rank_score' => $rank_score];
    }

    public function plugInfo ($id)
    {
        //check is_new
        $p_id = Plug::where('id',$id)->select('is_new','plug_id')->first();
        if($p_id->is_new === 0){
            // old
            $id = Plug::where('plug_id',$p_id->plug_id)->where('is_new',1)->value('id');
        }
        $plug = Plug::with('thumbs')
            ->with('tag_one')->with('tag_two')->with('user')
            ->with(['historys' => function ($query) {
                $query->where('is_new',0)->select('id','plug_id','version','game_version','created_at','title','name','type','is_new')->latest()->skip(0)->take(10);
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

        if ($plug->user_id !== Auth::id() && Auth::user()->is_admin === 0) {
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
            return ['sta' => 0, 'msg' => '您已收藏过'];
        }
        DB::beginTransaction();
        try {
            $user->collect_plug()->attach($id);
            $collect = Plug::where('plug_id',$id)->increment('collect_num');
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

    public function no_collect_this ($id)
    {
        $user = User::find(Auth::id());

        $collect_plug = DB::table('collect_plugs')->where('user_id',Auth::id())->where('plug_id',$id)->count();

        if ($collect_plug === 0) {
            return ['sta' => 0, 'msg' => '您还未收藏'];
        }
        DB::beginTransaction();
        try {
            $user->collect_plug()->detach($id);
            $collect = Plug::where('plug_id',$id)->decrement('collect_num');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '取消收藏失败'];
        }


        if ($collect) {
            return ['sta' => 1, 'msg' => '取消收藏成功'];
        }
        return ['sta' => 0, 'msg' => '取消收藏失败'];

    }

    /**
     * @param $id
     * @return array
     * 推荐
     */
    public function like_this ($id)
    {
        $user = User::find(Auth::id());

        $like_plug = DB::table('like_plugs')->where('user_id',Auth::id())->where('plug_id',$id)->count();

        if ($like_plug > 0) {
            return ['sta' => 0, 'msg' => '你已推荐过'];
        }

        DB::beginTransaction();
        try {
            $user->like_plug()->attach($id);
            $collect = Plug::where('plug_id',$id)->increment('like_num');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '推荐失败'];
        }

        del_cache();
        if ($collect) {
            return ['sta' => 1, 'msg' => '推荐成功'];
        }
        return ['sta' => 0, 'msg' => '推荐失败'];
    }


    /**
     * @param $id
     * 下载插件
     */
    public function download ($id)
    {
        $plug = Plug::select('type', 'is_free', 'title', 'content', 'gold', 'plug_id')->find($id);
        // 是否是免费的
        if ($plug->is_free == 0) {
            del_cache();
            Plug::where('plug_id',$plug->plug_id)->increment('download_num');
            // 是否是免费的
            $this->download_num($plug->plug_id);
            if ($plug->type == 1 || $plug->type == 2  || $plug->type == 4) {
                // wa tmw  model
                return ['sta' => 1, 'type' => 1, 'info' => $plug];
            } else {
                // download
                return ['sta' => 1, 'type' => 2, 'info' => $plug];
            }

        } else {
            // 查看 orders 表是否存在 该订单 存在则不再收费 用唯一ID 去查询
            if (Order::where([['plug_only_id', $plug->plug_id], ['user_id', Auth::id()]])->count() > 0) {
                // 曾经购买过
                del_cache();
                Plug::where('plug_id',$plug->plug_id)->increment('download_num');
                $this->download_num($plug->plug_id);
                if ($plug->type == 1 || $plug->type == 2 || $plug->type == 4) {
                    // wa tmw  model
                    return ['sta' => 1, 'type' => 1, 'info' => $plug];
                } else {
                    // download
                    return ['sta' => 1, 'type' => 2, 'info' => $plug];
                }
            }
            return ['sta' => 1, 'type' => 3, 'info' => $plug];
        }
    }

    public function quick_download ($id)
    {
        $plug = Plug::select('type', 'is_free', 'title', 'content', 'gold', 'plug_id')->find($id);
        Plug::where('plug_id',$plug->plug_id)->increment('download_num');
        $this->download_num($plug->plug_id);
        if ($plug->type == 1 || $plug->type == 2 || $plug->type == 4) {
            // wa tmw  model
            return ['sta' => 1, 'type' => 1, 'info' => $plug];
        } else {
            // download
            return ['sta' => 1, 'type' => 2, 'info' => $plug];
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

        //check user gold
        $user = Auth::user();
        if ($user->gold < $plug->gold) {
            return ['sta' => 0, 'msg' => '金币不足，请先充值'];
        }

        //pay
        DB::beginTransaction();
        try {
            Order::create([
                'plug_id' => $plug->id,
                'plug_only_id' => $plug->plug_id,
                'user_id' => $user->id,
                'gold' => $plug->gold,
                'status' => 1,
                'type' => $plug->type
            ]);

            User::where('id', $user->id)->update([
                'gold' => $user->gold - $plug->gold
            ]);


            $author = User::where('id', $plug->user_id)->first();

            $fc = Tool::where('name','fc')->value('value');

            if($fc){
                if($plug->gold === 1){
                    $gold_to_author = 1;
                }else{
                    $gold_to_author = floor( $plug->gold * $fc / 100 );
                }
            }else{
                $gold_to_author = $plug->gold;
            }

            User::where('id', $author->id)->update([
                'gold' => $author->gold + $gold_to_author
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
        $tag = [[1, 1, 'WA'], [1,2,'TMW'] , [2, 3, '魔兽插件'], [3, 4, 'ElvUI']];

        $res = [];
        foreach ($tag as $k => $v) {
            $res[$k]['value'] = $v[1]; // type 1 WA 2 TMW 3 插件 4ElvUI
            $res[$k]['label'] = $v[2];
            $res[$k]['children'] = Tag::with(['children' => function ($query) {
                $query->select(DB::raw('tags.id as value , tags.name as label ,  tags.pid , tags.id'));
            }])->select(DB::raw('tags.id as value , tags.name as label , tags.pid , tags.id'))->where('type', $v[0])->where('pid', 0)->where([['status', 1], ['is_check', 1]])
                ->when(Auth::user()->is_admin === 0 , function ($query){
                    $query->where('is_for_user', 1);
                })
                ->orderBy('rank','asc')->oldest()
                ->get();
        }

        $game_version = Tool::where('name','game_version')->orderBy('value','desc')->get();

        return ['res'=>$res , 'game_versions' =>$game_version];
    }

    public function plug_all_info_type($type,$name)
    {
        if($type === 'wa'){
            $tag = [[1, 1, 'WA']];
            $name = 'null';
        }else if($type === 'tmw'){
            $tag = [ [1,2,'TMW']];
            $name = 'null';
        }else if($type === 'addons'){
            $tag = [ [2, 3, '魔兽插件']];
        }else if($type === 'elvui'){
            $tag = [ [3, 4, 'ElvUI']];
        } else {
            $tag = [[1, 1, 'WA'], [1,2,'TMW'], [2, 3, '魔兽插件'], [3, 4, 'ElvUI']];
            $name = 'null';
        }

        $res = [];
        foreach ($tag as $k => $v) {
            $res[$k]['value'] = $v[1]; // type 1 WA 2 TMW 3 插件
            $res[$k]['label'] = $v[2];
            $res[$k]['children'] = Tag::with(['children' => function ($query) {
                $query->select(DB::raw('tags.id as value , tags.name as label ,  tags.pid , tags.id'));
            }])->select(DB::raw('tags.id as value , tags.name as label , tags.pid , tags.id'))->where('type', $v[0])->where('pid', 0)->where([['status', 1], ['is_check', 1]])
                ->when(isset($name) && $name !== 'null',function($query) use ($name) {
                    $query->where('name','整合界面')->orWhere('name','原创插件')->orWhere('name','怀旧插件')
                        ->orderBy("rank",'desc');
                })
                ->when(Auth::user()->is_admin === 0 , function ($query){
                    $query->where('is_for_user', 1);
                })
                ->orderBy('rank','asc')->oldest()
                ->get();
        }

        $game_version = Tool::where('name','game_version')->orderBy('value','desc')->get();


        return ['res'=>$res , 'game_versions' =>$game_version];
    }

    public function plug_all_info_for_admin()
    {
        $tag = [[1, 1, 'WA/TMW'], [3, 4, 'ElvUI'] , [2, 3, '魔兽插件']];

        $res = [];
        foreach ($tag as $k => $v) {
            $res[$k]['value'] = $v[1]; // type 1 WA 2 TMW 3 插件
            $res[$k]['label'] = $v[2];
            $res[$k]['children'] = Tag::with(['children' => function ($query) {
                $query->select(DB::raw('tags.id as value , tags.name as label ,  tags.pid , tags.id'));
            }])->select(DB::raw('tags.id as value , tags.name as label , tags.pid , tags.id'))->where('type', $v[0])->where('pid', 0)->where([['status', 1], ['is_check', 1]])
                ->orderBy('rank','asc')->oldest()->get();
        }

        $game_version = Tool::where('name','game_version')->orderBy('value','desc')->get();

        return ['res'=>$res , 'game_versions' =>$game_version];

    }

    public function plug_all_info_no_login()
    {
        $tag = [[1, 1, 'WA'] , [1,2,'TMW'] , [3,4,'ElvUI']];

        $res = [];
        foreach ($tag as $k => $v) {
            $res[$k]['value'] = $v[1]; // type 1 WA 2 TMW 3 插件
            $res[$k]['label'] = $v[2];
            $res[$k]['children'] = Tag::with(['children' => function ($query) {
                $query->select(DB::raw('tags.id as value , tags.name as label ,  tags.pid , tags.id'));
            }])->select(DB::raw('tags.id as value , tags.name as label , tags.pid , tags.id'))->where('type', $v[0])->where('pid', 0)->where([['status', 1], ['is_check', 1], ['is_for_user', 1]])
                ->orderBy('rank','asc')->oldest()->get();
        }

        $game_version = Tool::where('name','game_version')->orderBy('value','desc')->get();

        return ['res'=>$res , 'game_versions' =>$game_version];
    }

    public function plug_all_info_nav()
    {
        $tag = [[1, 1, 'WA'] , [1,2,'TMW'], [2, 3, '魔兽插件'], [3, 4, 'ElvUI']];

        $res = [];
        foreach ($tag as $k => $v) {
            $res[$k]['value'] = $v[1]; // type 1 WA 2 TMW 3 插件
            $res[$k]['label'] = $v[2];
            $res[$k]['children'] = Tag::with(['children' => function ($query) {
                $query->select(DB::raw('tags.id as value , tags.name as label ,  tags.pid , tags.id'));
            }])->select(DB::raw('tags.id as value , tags.name as label , tags.pid , tags.id'))->where('type', $v[0])->where('pid', 0)->where([['status', 1], ['is_check', 1]])
                ->orderBy('rank','asc')->oldest()->get();
        }

        $game_version = Tool::where('name','game_version')->orderBy('value','desc')->get();

        return ['res'=>$res , 'game_versions' =>$game_version];
    }

    /**
     * @param Request $request
     * @return array
     * 上传插件
     */
    public function upload_plug (Request $request, $plug_id = 0)
    {
        //check email
        if(Auth::user()->is_active === 0){
            return ['sta' => 0, 'msg' => '上传失败,请先激活邮箱'];
        }
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
        $plug->author = isset($req['author']) ? $req['author'] : '';
        $Plug->plug_id = ($plug_id === 0 || $plug_id === 'undefined') ? get_only_one_plug_id() : $plug_id;
        $Plug->gold = !$req['is_free'] ? 0 : $req['gold'];
        $Plug->type = $type['type'][0];
        $Plug->type_one = $type['type'][1];
        $Plug->is_check = !$req['is_free'] ? 1 : 0;
        $Plug->version = $type['type'][0] === 3 ? $req['version'] : date('YmdHi');
        $Plug->type_two = isset($type['type'][2]) ? $type['type'][2] : 0;
        $Plug->content = ($type['type'][0] === 1 || $type['type'][0] === 2 || $type['type'][0] === 4 ) ? $req['content'] : $req['plug_url'];  // 分字符串 跟下载链接
        DB::beginTransaction();
        try {
            if ($plug_id !== 0 && $plug_id !== 'undefined') {
                // 升级插件 历史插件 is_new  = 0
                Plug::where('plug_id', $plug_id)->update(['is_new' => 0]);
                $num = Plug::where('plug_id', $plug_id)->select('download_num','like_num','collect_num','score','version')->first();
                if($Plug->version === $num->version && $type['type'][0] !== 3){
                    $Plug->version = $Plug->version ."_".date('s');
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

        if($Plug->is_check === 0){
            dispatch(new \App\Jobs\SendPlugCheckEmail($Plug));
        }
        return ['sta' => 1, 'msg' => $Plug->is_check === 1 ? '分享成功' : '分享成功，请等待审核！'];
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
        $plug->author = isset($req['author']) ? $req['author'] : '';
        $Plug->gold = !$req['is_free'] ? 0 : $req['gold'];
        $Plug->type = $type['type'][0];
        $Plug->type_one = $type['type'][1];
        $Plug->is_check = !$req['is_free'] ? 1 : 0;
        $Plug->type_two = isset($type['type'][2]) ? $type['type'][2] : 0;
        $Plug->version = $type['type'][0] === 3 ? $req['version'] : $Plug->version;
        $Plug->content = ($type['type'][0] === 1 || $type['type'][0] === 2 || $type['type'][0] === 4) ? $req['content'] : $req['plug_url'];  // 分字符串 跟下载链接
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

        if($Plug->is_check === 0){
            dispatch(new \App\Jobs\SendPlugCheckEmail($Plug));
        }

        return ['sta' => 1, 'msg' => $Plug->is_check === 1 ? '更新成功' : '更新成功，请等待审核！'];
    }


    public function check_plug_id ($id)
    {
        $plug = Plug::with('thumbs')
            ->with('tag_one')->with('tag_two')->with('user')->with(['like_plug' => function ($query) {
                $query->where('users.id', Auth::id());
            }])->with(['collect_plug' => function ($query) {
                $query->where('users.id', Auth::id());
            }])->with(['is_pay' => function ($query) {
                $query->where('orders.user_id', Auth::id());
            }])->where('is_new',1)->where('plug_id',$id)->first();

        $plug->is_free = $plug->is_free === 0 ? false : true;
        $plug->type = [$plug->type, $plug->type_one, $plug->type_two];
        if ($plug && $plug->user_id === Auth::id())
            return ['sta' => 1 , 'plug'=>$plug];
        return ['sta' => 0];
    }

    public function check_version(Request $request , $plug_id = 0)
    {
        if(count(explode(".",$request->version)) === 2 && strlen($request->version) === 3 && explode(".",$request->version)[1] >= '0'){

            if($plug_id !== 0 && $plug_id !== 'undefined'){
                // update
                $plug = Plug::where('plug_id',$plug_id)->where('is_new',1)->first();
                if($request->version <= $plug->version ){
                    return ['sta'=>0 , 'msg'=>'版本号要大于上一版本'];
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
            return ['sta' => 0, 'msg' => '你已评分过'];
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
            $wa = Plug::where('is_new',1)->where('type',1)->skip(0)->take(20)->select('id','title','created_at','download_num')->where([['status', 1], ['is_check', 1]])->latest()->get();
            Cache::put('plug_index_wa',$wa,60);
        }

        if(Cache::has('plug_index_tmw')){
            $tmw = Cache::get('plug_index_tmw');
        }else{
            $tmw = Plug::where('is_new',1)->where('type',2)->skip(0)->take(20)->select('id','title','created_at','download_num')->where([['status', 1], ['is_check', 1]])->latest()->get();
            Cache::put('plug_index_tmw',$tmw,60);
        }

        if(Cache::has('plug_index_plug')){
            $plug = Cache::get('plug_index_plug');
        }else{
            $plug = Plug::where('is_new',1)->where('type',3)->skip(0)->take(20)->select('id','title','created_at','download_num')->where([['status', 1], ['is_check', 1]])->latest()->get();
            Cache::put('plug_index_plug',$plug,60);
        }

        if(Cache::has('plug_index_elvuis')){
            $elvuis = Cache::get('plug_index_elvuis');
        }else{
            $elvuis = Plug::where('is_new',1)->where('type',4)->skip(0)->take(20)->select('id','title','created_at','download_num')->where([['status', 1], ['is_check', 1]])->latest()->get();
            Cache::put('plug_index_elvuis',$elvuis,60);
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
            $recent_plugs = Plug::where('is_new',1)->skip(0)->take(20)->select('id','title','created_at','download_num','user_id')->with('user')->where([['status', 1], ['is_check', 1]])->latest()->get();
            Cache::put('plug_index_recent_plugs',$recent_plugs,60);
        }

        if(Cache::has('plug_index_total_person')){
            $total_person = Cache::get('plug_index_total_person');
        }else{
            $total_person = 0;
        }

        if(Cache::has('plug_index_download_plugs')){
            $download_plugs = Cache::get('plug_index_download_plugs');
        }else{
            $download_plugs = Plug::where('is_new',1)->skip(0)->take(20)->select('id','title','created_at','download_num')
                ->where([['status', 1], ['is_check', 1]])
                ->orderBy('download_num','desc')
                ->latest()
                ->get();
            Cache::put('plug_index_download_plugs',$download_plugs,60);
        }

        if(Cache::has('plug_index_download_plugs_this_mouth')){
            $download_plugs_this_mouth = Cache::get('plug_index_download_plugs_this_mouth');
        }else{
            $download_plugs_this_mouth = Plug::where('is_new',1)
                ->where([['status', 1], ['is_check', 1]])
                ->skip(0)->take(20)->select('plugs.id','plugs.title','plugs.created_at' , 'downloads.num')->
            leftJoin('downloads', 'plugs.plug_id' ,'=' ,'downloads.plug_id')
                ->where('mouth',date('Y-m',time()))
                ->orderBy('downloads.num','desc')
                ->orderBy('plugs.created_at','desc')
                ->get();

            foreach ($download_plugs_this_mouth as $k => $v){
                $download_plugs_this_mouth[$k]->num = number_format($v->num);
            }
            Cache::put('plug_index_download_plugs_this_mouth',$download_plugs_this_mouth,60);
        }

        if(Cache::has('plug_index_census')){
            $census = Cache::get('plug_index_census');
        }else{
            $census['plugs_count'] = Plug::where([['status', 1], ['is_check', 1],['is_new',1]])->count();
            $census['was_count'] = Plug::where([['status', 1], ['is_check', 1],['is_new',1]])->where('type',1)->count();
            $census['tmws_count'] = Plug::where([['status', 1], ['is_check', 1],['is_new',1]])->where('type',2)->count();
            $census['elvui_count'] = Plug::where([['status', 1], ['is_check', 1],['is_new',1]])->where('type',4)->count();
            $census['youxi_count'] = Plug::where([['status', 1], ['is_check', 1],['is_new',1]])->where('type',3)->count();
            $census['today_count'] = Plug::where([['status', 1], ['is_check', 1],['is_new',1]])->whereRaw('TO_DAYS( NOW( ) ) - TO_DAYS( created_at ) <= 1')->count();
            $census['last_time'] = Plug::where([['status', 1], ['is_check', 1],['is_new',1]])->orderBy('created_at','desc')->value('created_at');
            if($census['last_time']){
                $census['last_time'] = $census['last_time'];
            }else{
                $census['last_time'] = '暂无';
            }
            $census['useCount'] = User::count();
            $census['lm_count'] = User::where('camp',1)->count();
            $census['bl_count'] = User::where('camp',2)->count();
            Cache::put('plug_index_census',$census,60);
        }

        $today_time = Date('Y-m-d');
        return ['was'=>$wa,'tmws'=>$tmw,'plugs'=>$plug, 'elvuis'=>$elvuis ,'today_time'=>$today_time,'recent_plugs'=>$recent_plugs,'download_plugs'=>$download_plugs,'new_user'=>$user,'download_plugs_this_mouth'=>$download_plugs_this_mouth,'census'=>$census ,'total_person'=>$total_person];
    }

    public function delete($id)
    {
        $type = Plug::where('plug_id',$id)->value('type');
        $ids = Plug::where('plug_id',$id)->pluck('id');
        $content = Plug::where('plug_id',$id)->pluck('content');
        $thumbs = Thumb::whereIn('plug_id',$ids)->pluck('thumb');
         DB::beginTransaction();
         try{
             // 删除thumb数据库
             Thumb::whereIn('plug_id',$ids)->delete();
             // 删除plug
             Plug::where('plug_id',$id)->delete();
             DB::commit();
         }catch(\Exception $e){
             DB::rollBack();
             return ['sta'=>0 , 'msg'=>'删除失败'];
         }

        try{
            //删除图片
            foreach ($thumbs as $k => $v){
                unlink(substr($v,1));
            }
            //删除文件
            if(count($content) > 0 && $type == 3){
                foreach ($content as $k => $v){
                    unlink(substr($v,1));
                }
            }
        }catch(\Exception $e){
        }

        return ['sta'=>1 , 'msg'=>'删除成功'];
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
            ->when($request->search['gold'] == '1', function ($query) use ($request) {
                return $query->where('plugs.gold',0);
            })
            ->when($request->search['gold'] == '2', function ($query) use ($request) {
                return $query->where('plugs.gold', '>' ,0);
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
        $plug = Plug::find($id);
        $plug->rank = $rank;
        $plug = $plug->save();
        if($plug)
            return ['sta'=>1,'msg'=>'更新成功'];
        return ['sta'=>0,'msg'=>'更新失败'];
    }


    public function change_status($id, $v)
    {
        $plug = Plug::find($id);
        $plug->status = $v;
        $tag = $plug->save();
        if($tag)
            return ['sta'=>1, 'msg'=>'更新成功'];
        return ['sta'=>0, 'msg'=>'更新失败'];
    }

    public function change_is_check($id, $v)
    {
        $plug = Plug::find($id);
        $plug->is_check = $v;
        $tag = $plug->save();
        if($tag)
            return ['sta'=>1, 'msg'=>'更新成功'];
        return ['sta'=>0, 'msg'=>'更新失败'];
    }


    public function p_del($id)
    {
        Plug::where('plug_id',$id)->where('is_new',1)->update([
            'status' => 0
        ]);

        PlugDel::create([
            'plug_id' => $id,
            'user_id' => Auth::id()
        ]);

        return ['sta'=>1];
    }

    public function p_no_del($id)
    {
        Plug::where('plug_id',$id)->where('is_new',1)->update([
            'status' => 1
        ]);

        PlugDel::where('plug_id',$id)->delete();

        del_cache();
        return ['sta'=>1];
    }

    public function del_plugs()
    {
        $plug = PlugDel::oldest()->first();

        Log::info(json_encode($plug));
        Log::info(time() - strtotime($plug->created_at));
        if(time() - strtotime($plug->created_at) >= 24*60*60){
            // DEL
            $plugs = Plug::where('plug_id',$plug->plug_id)->get();

            $id = $plugs->pluck('id');

            $url = $plugs[0]->type === 3 ? $plugs->pluck('content') : [];

            $thumbs = Thumb::whereIn('plug_id',$id)->get();

            DB::beginTransaction();
            try{
                Plug::where('plug_id',$plug->plug_id)->delete();
                Thumb::whereIn('plug_id',$id)->delete();
                PlugDel::where('id',$plug->id)->delete();
                DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return ['sta'=>0 , 'msg'=>'失败'];
            }

            try{
                foreach ($thumbs as $k => $v){
                    unlink(substr($v,1));
                }
                //删除文件
                if(count($url) > 0){
                    foreach ($url as $k => $v){
                        unlink(substr($v,1));
                    }
                }
            }catch(\Exception $e){
            }

            return $url;

        }

    }

    public function search(Request $request)
    {
        $limit = isset($request->page) ? ($request->page - 1) * $request->size : 0;

        $plugs = Plug::where('title','like',"%{$request->keyword}%")->distinct()->where('is_new',1)->where([['plugs.status', 1], ['plugs.is_check', 1]])->join('users',function ($join) use ($request) {
            $join->on('users.id','plugs.user_id');
        })->orWhere(function ($query) use ($request) {
            $query->where('users.name','like',"%{$request->keyword}%")->where('is_new',1)->where([['plugs.status', 1], ['plugs.is_check', 1]]);
        })
            ->select('plugs.*' , 'users.id as uid' ,'users.name')->with('tag_one')->with('tag_two')
            ->skip($limit)->take($request->size)->with(['is_pay' => function ($query) {
                $query->where('orders.user_id', Auth::id());
            }])->orderBy('download_num','desc')->get();

        $count  = Plug::where('title','like',"%{$request->keyword}%")->distinct()->where('is_new',1)->where([['plugs.status', 1], ['plugs.is_check', 1]])->join('users',function ($join) use ($request) {
            $join->on('users.id','plugs.user_id');
        })->orWhere(function ($query) use ($request) {
            $query->where('users.name','like',"%{$request->keyword}%")->where('is_new',1)->where([['plugs.status', 1], ['plugs.is_check', 1]]);
        })->select('plugs.*' , 'users.id as uid' ,'users.name')->count();

        return ['list'=>$plugs ,'count'=>$count];
    }
}
