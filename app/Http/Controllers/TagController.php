<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    //
    public function index($type)
    {
        $type = config('my.tag_type')[$type];
        $tags = Tag::where('type',$type)->where('pid',0)->where([['status' , 1] , ['is_check' , 1]])->with('tags')->get();
        return $tags;
    }

    public function create(Request $request)
    {

        if(count($request->data['type']) === 1){
            $pid = 0;
        }else if(count($request->data['type']) === 2){
            $pid = $request->data['type'][1];
        }else{
            $pid = $request->data['type'][2];
        }

        Tag::create([
           'name' => $request->data['name'],
           'thumb' => is_null($request->data['thumb']) ? '' : $request->data['thumb'],
           'pid' => $pid,
           'type' => $request->data['type'][0] == 1 || $request->data['type'][0] == 2 ? 1 : 2,
           'status' => 1,
           'is_check' => 1,
        ]);
        return $request->data['name'];
    }

//    public function get_tag_by_type($type)
//    {
//        $type = $type == 1 || $type == 2 ? 1 : 2;
//        $res =
//        return $type;
//    }
}
