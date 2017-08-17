<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //

    public function index()
    {
        if(isset(Auth::user()->is_admin) && Auth::user()->is_admin === 1){
            return view('admin.index');
        }
        return redirect('/#/home');
    }

    public function loign()
    {
        if(!Auth::check()){
            return view('auth.admin');
        }else{
            return redirect('/');
        }
    }

    public function do_loign(Request $request)
    {
        $message = [
          'captcha.required' => '验证码不能为空',
          'captcha.captcha' => '验证码错误',
        ];
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha'
        ],$message);

        $user = User::where('email',$request->email)->first();
        if(!$user) {
            return back()->withErrors(['login'=>'用户名不存在']);
        }else{
            if(!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['login'=>'密码错误']);
            }else{
                if($user->is_admin === 1){
                    // login
                    Auth::login($user);
                    return redirect('/admin#/admin');
                }else{
                    return back()->withErrors(['login'=>'禁止非管理员登录']);
                }
            }
        }

        return $request->all();
    }
}
