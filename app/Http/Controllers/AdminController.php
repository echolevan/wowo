<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //

    public function index ()
    {
        if (isset(Auth::user()->is_admin) && Auth::user()->is_admin === 1) {
            return view('admin.index');
        }
        return redirect('/admin/login');
    }

    public function loign ()
    {
        if (!Auth::check()) {
            return view('auth.admin');
        } else {
            return redirect('/');
        }
    }

    public function do_loign (Request $request)
    {
        // put cache login
        $cache_name = 'login_' . request()->getClientIp();
        $num = 1;
        if (Cache::has($cache_name)) {
            $num = Cache::get($cache_name);
            if ($num > 3) {
                return back()->withErrors(['login_error' => '失败次数太多，请1分钟之后再试']);
            }
        }
        Cache::put($cache_name, $num + 1, 1);

        $message = [
            'password.required' => '密码不能为空',
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '验证码错误',
            'email.required' => '用户名不能为空',
            'email.string' => '用户名错误',
        ];
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha'
        ], $message);

        $user = User::where('id', $request->email)->orWhere('name', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['login' => '用户名不存在']);
        } else {
            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['login' => '密码错误']);
            } else {
                if ($user->is_admin === 1) {
                    Cache::forget($cache_name);
                    // login
                    Auth::login($user);
                    return redirect('/admin#/admin');
                } else {
                    return back()->withErrors(['login' => '禁止非管理员登录']);
                }
            }
        }

        return $request->all();
    }
}
