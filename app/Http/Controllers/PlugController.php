<?php

namespace App\Http\Controllers;

use App\Order;
use App\Plug;
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

             return ['sta'=>0 , 'type'=>3];

        }
    }
}
