<?php

namespace App\Http\Controllers;

use App\Lv;
use App\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    //
    public function index()
    {
        $tools = Tool::get();

        $arr = [];
        foreach ($tools as $k=>$v){
            $arr[$v->name] = $v;
        }

        return ['sta'=>1 , 'tools'=>$arr];
    }

    public function create(Request $request)
    {
        foreach ($request->data as $k => $v){
            $count = Tool::where('name',$k)->count();
            if($count > 0){
                Tool::where('name',$k)->update([
                    'value' => $v
                ]);
            }else{
                Tool::create([
                    'name' => $k,
                    'value' => $v,
                ]);
            }
        }

        $tools = Tool::get();

        return ['sta'=> 1 , 'msg'=>'更新成功'];
    }

    public function lv_list(Request $request , $page , $size)
    {
        $where = Lv::when($request->search['name'] != null, function ($query) use ($request) {
            return $query->where('name', 'like' , '%'.$request->search['name'].'%');
        });
        $count = $where->count();
        $list = $where->skip(($page - 1) * $size)->take($size)->latest()->get();
        return ['sta'=>1 , 'count' => $count , 'list'=>$list];
    }


    public function lv_create(Request $request)
    {
        $lv = Lv::create([
            'name' => $request->data['name'],
            'money' => $request->data['money'],
            'giving' => $request->data['giving'],
        ]);
        if($lv){
            return ['sta'=>1 , 'msg'=>'添加成功'];
        }
        return ['sta'=>0 , 'msg'=>'添加失败'];
    }

    public function lv_update(Request $request , $id)
    {
        $lv = Lv::where('id',$id)->update([
            'name' => $request->data['name'],
            'money' => $request->data['money'],
            'giving' => $request->data['giving'],
        ]);
        if($lv){
            return ['sta'=>1 , 'msg'=>'编辑成功'];
        }
        return ['sta'=>0 , 'msg'=>'编辑失败'];
    }

    public function check_name($name,$id)
    {
        $lv = Lv::where('name',$name)->when($id !== 0 , function($query) use ($id) {
            $query->where('id','!=',$id);
        })->count();
        if($lv > 0){
            return ['sta'=>0];
        }
        return ['sta'=>1];
    }

    public function check_money($money,$id)
    {
        $lv = Lv::where('money',$money)->when($id !== 0 , function($query) use ($id) {
            $query->where('id','!=',$id);
        })->count();
        if($lv > 0){
            return ['sta'=>0];
        }
        return ['sta'=>1];
    }

    public function del_lv($id)
    {
        $lv = Lv::where('id',$id)->delete();
        if($lv)
            return ['sta'=>1 , 'msg'=>'删除成功'];
        return ['sta'=>0 , 'msg'=>'删除失败'];

    }

    public function nickname()
{
    $nickname = Tool::where('name','nickname')->get();
    return $nickname;
}

    public function create_nickname($name)
    {
        $count = Tool::where('name','nickname')->where('value',$name)->count();
        if($count > 0){
            return ['sta'=>1 , 'msg'=>'昵称已经存在'];
        }
        $nick = Tool::create([
            'name'=> 'nickname',
            'value' => $name
        ]);

        if($nick)
            return ['sta'=>1 , 'msg'=>'添加成功'];
        return ['sta'=>0 , 'msg'=>'添加失败'];
    }

    public function del_nickname($id)
    {
        $lv = Tool::where('id',$id)->delete();
        if($lv)
            return ['sta'=>1 , 'msg'=>'删除成功'];
        return ['sta'=>0 , 'msg'=>'删除失败'];

    }

    public function game_version()
    {
        $nickname = Tool::where('name','game_version')->get();
        return $nickname;
    }

    public function create_game_version($name)
    {
        $count = Tool::where('name','game_version')->where('value',$name)->count();
        if($count > 0){
            return ['sta'=>1 , 'msg'=>'游戏版本号已经存在'];
        }
        $nick = Tool::create([
            'name'=> 'game_version',
            'value' => $name
        ]);

        if($nick)
            return ['sta'=>1 , 'msg'=>'添加成功'];
        return ['sta'=>0 , 'msg'=>'添加失败'];
    }

    public function del_game_version($id)
    {
        $lv = Tool::where('id',$id)->delete();
        if($lv)
            return ['sta'=>1 , 'msg'=>'删除成功'];
        return ['sta'=>0 , 'msg'=>'删除失败'];

    }

}
