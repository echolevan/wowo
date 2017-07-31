<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index()
    {


        if (Auth::check())
            return ['sta' =>'1' , 'info' => Auth::user()];
        return ['sta' => 0];
    }


    public function logout()
    {
        $user = Auth::user();
        Auth::logout($user);
        return ['sta' =>'1'];
    }

    public function check_email($token)
    {

        $user = User::where('token',$token)->update([
            'is_active' => 1,
            'token' => ''
        ]);

        if($user){
           return redirect(route('index'));
        }else{
            return redirect(abort(404));
        }
    }
}
