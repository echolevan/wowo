<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    //
    public function index ($type)
    {
        $type = config('my.tag_type')[$type];
        $tags = Tag::where('type', $type)->where('pid', 0)->where([['status', 1], ['is_check', 1]])->with('tags')->orderBy('rank', 'asc')->latest()->get();
        return $tags;
    }

    public function create (Request $request)
    {

        if (count($request->data['type']) === 1) {
            $pid = 0;
        } else if (count($request->data['type']) === 2) {
            $pid = $request->data['type'][1];
        } else {
            $pid = $request->data['type'][2];
        }

        Tag::create([
            'name' => $request->data['name'],
            'thumb' => is_null($request->data['thumb']) ? '' : $request->data['thumb'],
            'pid' => $pid,
            'type' => $request->data['type'][0] == 1 || $request->data['type'][0] == 2 ? 1 : 2,
            'status' => 1,
            'is_check' => 1,
            'is_for_user' => $request->data['is_for_user'],
        ]);
        return ['sta' => 1, 'msg' => '添加成功'];
    }

    public function update (Request $request, $id)
    {
        if (count($request->data['type']) === 1) {
            $pid = 0;
        } else if (count($request->data['type']) === 2) {
            $pid = $request->data['type'][1];
        } else {
            $pid = $request->data['type'][2];
        }

        Tag::where('id', $id)->update([
            'name' => $request->data['name'],
            'thumb' => is_null($request->data['thumb']) ? '' : $request->data['thumb'],
            'pid' => $pid,
            'type' => $request->data['type'][0] == 1 || $request->data['type'][0] == 2 ? 1 : 2
        ]);

        $tag = Tag::with('parent')->find($id);
        return ['sta' => 1, 'msg' => '添加成功', 'info' => $tag];
    }

    public function tag_list (Request $request, $page, $size)
    {
        $where = Tag::when($request->search['name'] != null, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search['name'] . '%');
        })
            ->when(isset($request->search['type'][0]) && $request->search['type'][0] != null, function ($query) use ($request) {
                $type = $request->search['type'][0] == 3 ? 2 : $request->search['type'][0];
                return $query->where('type', $type);
            })
            ->when(isset($request->search['type'][1]) && $request->search['type'][1] != null, function ($query) use ($request) {
                if($request->search['type'][0] == 3){
                    return $query->where('id', $request->search['type'][1]);
                }else{
                    return $query->where('pid', $request->search['type'][1]);
                }
            })
            ->when(isset($request->search['type'][2]) && $request->search['type'][2] != null, function ($query) use ($request) {
                return $query->where('id', $request->search['type'][2]);
            })
            ->when($request->search['is_for_user'] != null, function ($query) use ($request) {
                return $query->where('is_for_user', $request->search['is_for_user']);
            })
            ->when($request->search['status'] != null, function ($query) use ($request) {
                return $query->where('status', $request->search['status']);
            });
        $count = $where->count();
        $list = $where->with('parent')->skip(($page - 1) * $size)->take($size)->get();
        return ['sta' => 1, 'count' => $count, 'list' => $list];
    }

    public function change_status ($id, $v)
    {
        $tag = Tag::where('id', $id)->update([
            'status' => $v
        ]);
        if ($tag)
            return ['sta' => 1, 'msg' => '更新成功'];
        return ['sta' => 0, 'msg' => '更新失败'];
    }

    public function change_is_for_user ($id, $v)
    {
        $tag = Tag::where('id', $id)->update([
            'is_for_user' => $v
        ]);
        if ($tag)
            return ['sta' => 1, 'msg' => '更新成功'];
        return ['sta' => 0, 'msg' => '更新失败'];
    }

    public function change_rank ($id, $rank)
    {
        $plug = Tag::where('id', $id)->update([
            'rank' => $rank
        ]);
        if ($plug)
            return ['sta' => 1, 'msg' => '更新成功'];
        return ['sta' => 0, 'msg' => '更新失败'];
    }

    public function check_tag_name (Request $request, $id = 0)
    {
        $check = Tag::where('name', $request->name)->where('pid', $request->pid)->when($id != 0, function ($query) use ($id) {
            $query->where('id', '!=', $id);
        })->count();
        if ($check > 0)
            return ['sta' => 0];
        return ['sta' => 1];
    }

    public function delete ($id)
    {
        $info = Tag::find($id);

        if($info->pid === 0 ){
            $count = Tag::where('pid',$id)->count();
            if($count > 0){
                return ['sta' => 0, 'msg' => '删除失败！请先删除子项'];
            }
        }

        $is = Tag::where('id', $id)->delete();
        if ($is)
            return ['sta' => 1, 'msg' => '删除成功'];
        return ['sta' => 0, 'msg' => '删除失败'];
    }


}
