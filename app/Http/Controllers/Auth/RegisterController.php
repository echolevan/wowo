<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/#/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->redirectTo = request()->session()->pull('login_url', '/');
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'camp.required' => '请选择阵营',
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '验证码输入错误',
            'name.is_num' => '用户名不能为纯数字',
            'name.is_name_zw' => '用户名不能为汉字',
            'password.is_pass' => '密码必须有大小写字母+数字',
            'nickname.required' => '昵称不能为空',
            'nickname.max' => '昵称最长为10',
            'nickname.unique' => '昵称已经存在',
            'nickname.alpha_num' => '昵称不能含有符号',
            'name.is_admin_name' => '用户名违规',
            'nickname.is_admin_name' => '昵称违规',
        ];

        return Validator::make($data, [
            'name' => 'required|string|max:255|unique:users|alpha_num|is_num|is_admin_name|is_name_zw',
            'email' => 'required|string|email|max:255|unique:users',
            'nickname' => 'required|string|max:10|unique:users|alpha_num|is_admin_name',
            'password' => 'required|string|min:8|is_pass|confirmed',
            'captcha' => 'required|captcha',
            'camp' => 'required'
        ],$messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $token = str_random(60);

        $user =  User::create([
            'name' => $data['name'],
            'camp' => $data['camp'],
            'email' => $data['email'],
            'nickname' => $data['nickname'],
            'token' => $token,
            'login_at' => Carbon::now(),
            'avatar' => $data['camp'] == 1 ? '/images/avatar/1.jpg' : '/images/avatar/2.jpg',
            'password' => bcrypt($data['password']),
            'update_camp_at' => time(),
        ]);

        $user->notify(new \App\Notifications\UserCreated($user));

        return $user;

    }
}
