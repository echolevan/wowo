<?php

namespace App\Http\Controllers;

use App\Bm;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BmController extends Controller
{
    //
    public function bm_list (Request $request, $page, $size)
    {
        $where = Bm::when($request->search['title'] != null, function ($query) use ($request) {
            return $query->where('title', 'like', '%' . $request->search['title'] . '%');
        })
            ->when($request->search['type'] != null, function ($query) use ($request) {
                return $query->where('type', $request->search['type']);
            })
            ->when($request->search['zy_type'] != null, function ($query) use ($request) {
                return $query->where('zy_type', $request->search['zy_type']);
            })
            ->when($request->search['status'] != null, function ($query) use ($request) {
                return $query->where('status', $request->search['status']);
            })
            ->when($request->search['orderBySome'] != null, function ($query) use ($request) {
                return $query->orderBy($request->search['orderBySome'], $request->search['orderByF']);
            });
        $count = $where->count();
        $list = $where->with(['user'])->with(['order'=>function($query){
            $query->where('orders.type',4)->where('user_id',Auth::id());
        }])->skip(($page - 1) * $size)->take($size)->orderBy('updated_at','desc')->get();

        $today = date('Y-m-d');
        return ['sta' => 1, 'count' => $count, 'list' => $list ,'today'=>$today];
    }

    public function create (Request $request)
    {
        $bm = Bm::create([
            'title' => $request->data['title'],
            'url' => $request->data['url'],
            'user_id' => Auth::id(),
            'type' => $request->data['type'],
            'zy_type' => $request->data['zy_type'],
            'gold' => !$request->data['is_free'] ? 0 : $request->data['gold'],
        ]);

        if ($bm)
            return ['sta' => 1, 'msg' => '添加成功'];
        return ['sta' => 0, 'msg' => '添加失败'];
    }

    public function update (Request $request, $id)
    {
        $old_bum = Bm::find($id);
        if($request->data['url'] !== $old_bum->url){
            del_file(collect($old_bum->url));
        }
        $bm = Bm::where('id', $id)->update([
            'title' => $request->data['title'],
            'url' => $request->data['url'],
            'type' => $request->data['type'],
            'zy_type' => $request->data['zy_type'],
            'gold' => !$request->data['is_free'] ? 0 : $request->data['gold'],
        ]);
        if ($bm)
            return ['sta' => 1, 'msg' => '更新成功'];
        return ['sta' => 0, 'msg' => '更新失败'];
    }

    public function change_rank ($id, $rank)
    {
        $plug = Bm::where('id', $id)->update([
            'rank' => $rank
        ]);
        if ($plug)
            return ['sta' => 1, 'msg' => '更新成功'];
        return ['sta' => 0, 'msg' => '更新失败'];
    }

    public function change_status ($id, $v)
    {
        $tag = Bm::where('id', $id)->update([
            'status' => $v
        ]);
        if ($tag)
            return ['sta' => 1, 'msg' => '更新成功'];
        return ['sta' => 0, 'msg' => '更新失败'];
    }

    public function check_download($id)
    {
        $bm = Bm::find($id);

        if ($bm->gold === 0) {
            return ['sta' => 1];
        } else {
            // check has order
            $order = Order::where([['plug_id', $bm->id], ['user_id', Auth::id()], ['type', 4]])->count();
            if ($order > 0) {
                return ['sta' => 1];
            }
            if ($bm->gold > Auth::user()->gold) {
                return ['sta' => 9 , 'msg'=>'金币不足'];
            } else {
                return ['sta' => 2 , 'msg'=>'金币充足，请确认'];
            }
        }
    }

    public function download ($id)
    {
        $bm = Bm::find($id);

        if ($bm->gold === 0) {
            $bm->increment('download_num');
            return ['sta' => 1, 'msg' => '下载成功', 'url' => $bm->url];
        } else {
            // check has order
            $order = Order::where([['plug_id',$bm->id],['user_id',Auth::id()],['type',4]])->count();
            if($order > 0){
                $bm->increment('download_num');
                return ['sta' => 1, 'msg' => '下载成功', 'url' => $bm->url];
            }
            if($bm->gold > Auth::user()->gold){
                return ['sta'=>9 ,'msg'=>'您的金币不足，请先充值'];
            }else{
                 DB::beginTransaction();
                 try{
                    // create order
                     Order::create([
                         'plug_id' => $bm->id,
                         'plug_only_id' =>'bm',
                         'user_id' =>Auth::id(),
                         'gold' => $bm->gold,
                         'status' => 1,
                         'type' => 4
                     ]);

                     User::where('id',Auth::id())->update([
                         'gold' => Auth::user()->gold - $bm->gold
                     ]);
                     $bm->increment('download_num');
                     DB::commit();
                 }catch(\Exception $e){
                     DB::rollBack();
                     return ['sta'=>0 , 'msg'=>'下载失败'];
                 }
                return ['sta' => 1, 'msg' => '下载成功', 'url' => $bm->url];
            }
        }
    }


    public function delete($id)
    {
        $content = Bm::where('id',$id)->value('url');
        DB::beginTransaction();
        try{
            //删除文件
            del_file(collect($content));

            // 删除plug
            Bm::where('id',$id)->delete();
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return ['sta'=>0 , 'msg'=>'删除失败'];
        }

        return ['sta'=>1 , 'msg'=>'删除成功'];
    }

}
