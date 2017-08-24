<?php

namespace App\Http\Controllers;

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
}
