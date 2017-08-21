<?php

namespace App\Http\Controllers;

use App\Bm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BmController extends Controller
{
    //
    public function bm_list(Request $request , $page , $size)
    {
        $where = Bm::when($request->search['title'] != null, function ($query) use ($request) {
            return $query->where('title', 'like' , '%'.$request->search['title'].'%');
        })
            ->when($request->search['type'] != null, function ($query) use ($request) {
                return $query->where('type', $request->search['type']);
            })
            ->when($request->search['status'] != null, function ($query) use ($request) {
                return $query->where('status', $request->search['status']);
            })
            ->when($request->search['orderBySome'] != null, function ($query) use ($request) {
                return $query->orderBy($request->search['orderBySome'], $request->search['orderByF']);
            });
        $count = $where->count();
        $list = $where->with('user')->skip(($page-1)*$size)->take($size)->get();
        return ['sta'=>1, 'count'=>$count, 'list'=>$list];
    }

    public function create(Request $request)
    {
        $bm = Bm::create([
           'title' => $request->data['title'],
           'url' => $request->data['type'] == 1 ? $request->data['bm_url'] : $request->data['url'],
           'user_id' => Auth::id(),
           'type' => $request->data['type']
        ]);

        if($bm)
            return ['sta'=>1 , 'msg'=>'新增成功'];
        return ['sta'=>0 , 'msg'=>'新增失败'];
    }

    public function update(Request $request , $id)
    {
        $bm = Bm::where('id',$id)->update([
            'title' => $request->data['title'],
            'url' => $request->data['type'] == 1 ? $request->data['bm_url'] : $request->data['url'],
            'type' => $request->data['type']
        ]);
        if($bm)
            return ['sta'=>1 , 'msg'=>'编辑成功'];
        return ['sta'=>0 , 'msg'=>'编辑失败'];
    }

    public function change_rank($id, $rank)
    {
        $plug = Bm::where('id',$id)->update([
            'rank' => $rank
        ]);
        if($plug)
            return ['sta'=>1,'msg'=>'编辑成功'];
        return ['sta'=>0,'msg'=>'编辑失败'];
    }

    public function change_status($id, $v)
    {
        $tag = Bm::where('id',$id)->update([
            'status' =>$v
        ]);
        if($tag)
            return ['sta'=>1, 'msg'=>'更新成功'];
        return ['sta'=>0, 'msg'=>'更新失败'];
    }

}
