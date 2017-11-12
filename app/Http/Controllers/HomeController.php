<?php

namespace App\Http\Controllers;

use App\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // check xl
        $tools = Tool::where('name','xlm')->value('value');

        if(isset($tools) && $tools == 1){
            $xlm_wid = '10480';
            $xlm_uid = Auth::id() ? Auth::id() : 0;
            $xlm_name = Auth::user() ? Auth::user()->name : '';
            $xlm_avatar = Auth::user() ? Auth::user()->avatar : '';
            $time = time();
            $key = 'ejzZUZHfqJkQI5KqTbjbxvcZ98PhXRSK';
            $hash = hash("SHA512", $xlm_wid.'_'.$xlm_uid.'_'.$time.'_'.$key);
            $tool = 1;
            return view('welcome',compact('xlm_wid','xlm_uid','xlm_avatar','xlm_name','time','hash','tool'));
        }else{
            $tool = 0;
            return view('welcome',compact('tool'));
        }

    }
}
