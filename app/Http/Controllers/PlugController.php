<?php

namespace App\Http\Controllers;

use App\Order;
use App\Plug;
use App\Tag;
use App\Thumb;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $orderBy = 'collect_num';
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
                return $query->where('title' , 'like', "%$keyWord%");
            })
            ->where([['status', 1], ['is_check', 1]])
            ->skip($limit)->take(10)
            ->orderBy($orderBy, 'desc')
            ->get();

        $count = Plug::where('type', $type)
            ->when($tag_active != 0 || $tag_active_pid != 0, function ($query) use ($where, $tag_active, $tag_active_pid) {
                return $query->whereRaw($where);
            })
            ->when($keyWord != '', function ($query) use ($keyWord) {
                return $query->where('title' , 'like', "%$keyWord%");
            })
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
        $rank_download = Plug::when($type > 0 , function ($query) use ($type) {
            $query->where('type', $type);
        })->where([['status', 1], ['is_check', 1]])->skip(0)->take(5)->orderBy('download_num','desc')->get();

        $rank_score = Plug::when($type > 0 , function ($query) use ($type) {
            $query->where('type', $type);
        })->where([['status', 1], ['is_check', 1]])->skip(0)->take(5)->orderBy('score','desc')->get();

        return ['rank_download' => $rank_download, 'rank_score' => $rank_score];
    }

    public function plugInfo($id)
    {
        $plug = Plug::with('thumbs')
            ->with('tag_one')->with('tag_two')->with('user')->with(['like_plug'=>function($query){
                $query->where('users.id',Auth::id());
            }])->with(['collect_plug'=>function($query){
                $query->where('users.id',Auth::id());
            }])->find($id);

        $thumb_list = [];
        foreach ($plug->thumbs as $k => $v){
            $thumb_list[$k]['src'] = $v->thumb;
            $thumb_list[$k]['w'] = $v->width;
            $thumb_list[$k]['h'] = $v->height;
        }

        // 更新说明
        $updated_info = Plug::where('id' , $id)->select('updated_info', 'created_at')->get();

        //历史版本
        $history = Plug::where('plug_id' , $plug->plug_id)->where('is_new',0)->get();

        return ['plug'=>$plug , 'thumb_list'=>$thumb_list , 'updated_info'=>$updated_info , 'history'=>$history];
    }


    /**
     * @param $id
     * 收藏id
     */
    public function collect_this($id)
    {
        $user = User::with(['collect_plug'=>function($query) use ($id){
            $query->where('plugs.id',$id);
        }])->find(Auth::id());

        if(count($user->collect_plug) > 0){
            return ['sta'=>0 , 'msg'=>'你已经收藏过了'];
        }

        DB::beginTransaction();
        try{
            $user->collect_plug()->attach($id);
            $collect = Plug::find($id)->increment('like_num');
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return ['sta'=>0 , 'msg'=>'收藏失败'];
        }


        if($collect){
            return ['sta'=>1 , 'msg'=>'收藏成功'];
        }
        return ['sta'=>0 , 'msg'=>'收藏失败'];

    }

    /**
     * @param $id
     * @return array
     * 点赞
     */
    public function like_this($id)
    {
        $user = User::with(['like_plug'=>function($query) use ($id){
            $query->where('plugs.id',$id);
        }])->find(Auth::id());

        if(count($user->like_plug) > 0){
            return ['sta'=>0 , 'msg'=>'你已经点赞过了'];
        }

        DB::beginTransaction();
        try{
            $user->like_plug()->attach($id);
            $collect = Plug::find($id)->increment('collect_num');
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return ['sta'=>0 , 'msg'=>'点赞失败'];
        }


        if($collect){
            return ['sta'=>1 , 'msg'=>'点赞成功'];
        }
        return ['sta'=>0 , 'msg'=>'点赞失败'];
    }


    /**
     * @param $id
     * 下载插件
     */
    public function download($id)
    {
        $plug = Plug::select('type' , 'is_free' , 'title' , 'content')->find($id);
        // 是否是免费的
        if($plug->is_free == 0){
            Plug::find($id)->increment('download_num');
            // 是否是免费的
            if($plug->type == 1 || $plug->type == 2){
                // wa twm  model
                return ['sta'=>1 , 'type'=>1 , 'info'=>$plug];
            }else{
                // download
                return ['sta'=>1 , 'type'=>2 , 'info'=>$plug];
            }

        } else {
           // 收费的插件
//            if(!Auth::check()){
//                return ['sta'=>0 , 'msg'=>'请先登录'];
//            }
//
//            //check user wwb
//            $user = Auth::user();
//            if($user->wwb < $plug->wwb){
//                return ['sta'=>0 , 'msg'=>'窝窝币不足，请先充值'];
//            }
//
//            //pay
//             DB::beginTransaction();
//             try{
//                 Order::create([
//                     'plug_id' => $plug->id,
//                     'user_id' => $user->id,
//                     'wwb' => $plug->wwb,
//                     'status' => 1
//                 ]);
//
//                 User::where('id',$user->id)->update([
//                     'wwb' => $user->wwb - $plug->wwb
//                 ]);
//
//                 DB::commit();
//             }catch(\Exception $e){
//                 DB::rollBack();
//                 return ['sta'=>0 , 'msg'=>'支付失败'];
//             }

             return ['sta'=>1 , 'type'=>3];

        }
    }

    /**
     * @return array
     * 得到分类连动
     */
    public function plug_all_info()
    {
        $tag = [[1,1,'WA'] , [1,2,'TMW'] , [2,3,'插件']];

        $res = [];
        foreach($tag as $k => $v){
            $res[$k]['value'] = $v[1]; // type 1 WA 2 TMW 3 插件
            $res[$k]['label'] = $v[2];
            $res[$k]['children'] = Tag::with(['children'=>function($query){
                $query->select(DB::raw('tags.id as value , tags.name as label ,  tags.pid , tags.id'));
            }])->select(DB::raw('tags.id as value , tags.name as label , tags.pid , tags.id'))->where('type',$v[0])->where('pid',0)->where([['status' , 1] , ['is_check' , 1]])->get();
        }

        return $res;
    }

    /**
     * @param Request $request
     * @return array
     * 上传插件
     */
    public function upload_plug(Request $request)
    {
        $req = collect($request->data);
        $plug = $req->except('uploadList' , 'type' , 'content' , 'plug_url');
        $type = $req->only('type');
        $uploadList = $req->only('uploadList');

        // 开始写入值
        $Plug = new Plug;
        foreach ($plug as $k => $v){
            $Plug->$k = $v;
        }
        $Plug->user_id = Auth::id();
        $Plug->wwb = is_null($req['wwb']) ? 0 : $req['wwb'];
        $Plug->type = $type['type'][0];
        $Plug->type_one = $type['type'][1];
        $Plug->type_two = isset($type['type'][2]) ? $type['type'][2] : 0;
        $Plug->content = $type['type'][0] === 1 || $type['type'][0] === 2 ? $req['content'] : $req['plug_url'];  // 分字符串 跟下载链接

         DB::beginTransaction();
         try{
             $Plug->save();
             foreach ($uploadList['uploadList'] as $v){
                 Thumb::create([
                     'thumb' => $v['url'],
                     'plug_id' => $Plug->id,
                     'width' => $v['width'],
                     'height' => $v['height'],
                 ]);
             }
             DB::commit();
         }catch(\Exception $e){
             DB::rollBack();
             return ['sta'=>0 , 'msg'=>'添加失败'];
         }

        return ['sta'=>1 , 'msg'=>'上传成功'];
    }
}
