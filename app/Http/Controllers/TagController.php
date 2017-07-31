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
}
