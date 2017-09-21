<?php

namespace App\Http\Controllers;

use App\Http\Middleware\userLogin;
use App\Lv;
use App\Notifications\SendPasswordRestMail;
use App\Order;
use App\Plug;
use App\Recharge;
use App\Tool;
use App\User;
use App\Withdraws;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    //
    public function index()
    {

        $tools = Tool::get();

        $tool = [];
        foreach ($tools as $k=>$v){
            $tool[$v->name] = $v;
        }

        if (Auth::check()){
            $lv = $this->get_user_lv();
            $arr = explode('@',Auth::user()->email);
            $domain = $arr[1];
            return ['sta' =>'1' , 'info' => Auth::user() , 'email'=>'http://mail.'.$domain , 'tools'=>$tool , 'lv'=>$lv['info']];
        }

        return ['sta' => 0, 'tools'=>$tool];
    }


    public function logout()
    {
        $user = Auth::user();
        Auth::logout($user);
        return ['sta' =>'1'];
    }

    public function check_login_email(Request $request)
    {
        if($request->email){
            if(is_numeric($request->email)){
                $camp = User::where('id',$request->email)->value('camp');
            }else{
                $camp = User::where('name',$request->email)->value('camp');
            }
//            $camp = User::where('id',$request->email)->orWhere('name',$request->email)->value('camp');
            if($camp){
                return ['sta' => 1 , 'camp'=>$camp];
            }
            return ['sta' => 0];
        }
        return ['sta' => 0];
    }

    public function check_email($token)
    {

        $user = User::where('token',$token)->first();
        if($user){
            User::where('token',$token)->update([
                'is_active' => 1,
                'token' => ''
            ]);
            return redirect('/#/home')->withErrors(['is_active_ok' => $user->camp]);
        }else{
            return redirect('/#/home');
        }

    }

    /**
     * @param Request $request
     * 用户充值
     * config my ==== recharge_type
     */
    public function recharge(Request $request)
    {

        if($request->recharge_amount <= 0){
            // pay other amount
            $recharge_amount = $request->recharge_amount_other;
        }else{
            $recharge_amount = $request->recharge_amount;
        }

        $lv = $this->get_user_lv();
        // 生成充值订单
        $rec = Recharge::create([
            'user_id' => Auth::id(),
            'out_trade_no' => date('YmdHis') . time().rand(10000,99999),
            'recharge_type' => $request->recharge_type,
            'recharge_amount' => sprintf("%.2f",$recharge_amount),
            'recharge_gold' => $recharge_amount*10,
            'giving_gold' => floor($recharge_amount*10*$lv['info']->giving / 100),
            'status' => 0,
        ]);

        Log::info(json_encode($rec));

        if(!$rec){
            Log::error(json_encode($rec));
            return ['sta'=>0 , 'msg'=>'创建充值订单失败'];
        }
//
        if($rec->recharge_type == 1){
            // 支付宝
            return ['sta'=>1 , 'url'=>route('user.go_to_pay',$rec->id) , 'out_trade_no'=>$rec->out_trade_no,'type'=>'alipay'];
        }else if($rec->recharge_type == 2){
            // 微信
            // todo
            $payC = new PayController();
            $pay_url = $payC->wechat_pay($rec);
            $path = 'qrcodes/'.Auth::id().time().str_random(5).'.png';
            QrCode::format('png')->size(300)->margin(0)->merge('/public/images/pay/1.jpg', .2)->errorCorrection('L')->generate($pay_url, '../public/'.$path);
            return ['sta'=>1 , 'url'=>$path , 'out_trade_no'=>$rec->out_trade_no,'type'=>'wechat'];
        }

    }

    public function go_to_pay($id)
    {
        $order = Recharge::find($id);
        if($order->recharge_type == 1){
            // 支付宝
            // todo
            $payC = new PayController();
            $pay_url = $payC->alipay($order);
        }else if($order->recharge_type == 2){
            // 微信
            // todo
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function upload_avatar(Request $request)
    {

        $info = explode("/",$request->file('file')->getClientMimeType());

        if( !in_array( $request->file('file')->getClientMimeType() , config('my.img_type') ) ){
            return ['sta'=> 0 ,'msg'=>'请上传PNG、GIF、JPG格式的图片'];
        }
        if($request->file('file')->getSize() > 1024*1024*3){
            return ['sta'=> 0 ,'msg'=>'请上传小于3M的图片'];
        }

        $path = "image";
        $url = upload_avatar_img($request->file('file') , $path , [300,300] , end($info));

        if($url){
            User::where('id',Auth::id())->update([
                'avatar' => $url
            ]);

            $info = User::find(Auth::id());
        }
        return ['sta'=> 1 ,'info'=>$info];
    }


    public function update(Request $request)
    {
        $birthplace['province'] = $request->birthplace['province'] === '省' ? '' : $request->birthplace['province'];
        $birthplace['city']  = $request->birthplace['city'] === '市' ? '' : $request->birthplace['city'];
        $birthplace['area'] = $request->birthplace['area'] === '区' ? '' : $request->birthplace['area'];

        $habitably['province'] = $request->habitably['province'] === '省' ? '' : $request->habitably['province'];
        $habitably['city'] = $request->habitably['city'] === '市' ? '' : $request->habitably['city'];
        $habitably['area'] = $request->habitably['area'] === '区' ? '' : $request->habitably['area'];

        if($request->camp != Auth::user()->camp){

            $sta = $this->check_is_camp();
            if(!$sta['sta']){
                return ['sta'=> 0 ,'msg'=>'阵营切换间隔时间为 30 天' , 'time'=>$sta['time']];
            }

            $arr = [
                'nickname' => $request->nickname,
                'camp' => $request->camp,
                'info' => is_null($request->info) ? '' : $request->info,
                'update_camp_at' => time(),
                'sex' => $request->sex,
                'birthday' => $request->birthday ? Carbon::createFromTimestamp(strtotime($request->birthday))->toDateString() : '',
                'birthplace' => json_encode($birthplace),
                'habitably' => json_encode($habitably),
            ];
        }else{
            $arr = [
                'nickname' => $request->nickname,
                'info' => is_null($request->info) ? '' : $request->info,
                'sex' => $request->sex,
                'birthday' => $request->birthday ? Carbon::createFromTimestamp(strtotime($request->birthday))->toDateString() : '',
                'birthplace' => json_encode($birthplace),
                'habitably' =>  json_encode($habitably)
            ];
        }

        User::where('id',Auth::id())->update($arr);

        $info = User::find(Auth::id());

        return ['sta'=> 1 ,'info'=>$info];
    }

    public function check_is_camp()
    {
        if( !Auth::user()->update_camp_at || strtotime(date('Y-m-d 00:00:00',time())) - strtotime(date('Y-m-d 00:00:00',Auth::user()->update_camp_at)) > 30*60*60*24 )
            return ['sta'=> 1];
        return ['sta'=> 0 , 'time'=> 30 - floor( (strtotime(date('Y-m-d 00:00:00',time())) - strtotime(date('Y-m-d 00:00:00',Auth::user()->update_camp_at))) / (60*60*24) )];
    }


    /**
     * @param $page
     * @param $size
     * 我购买的插件
     */
    public function orders_pay($page, $size)
    {
        $count = Order::where('user_id',Auth::id())->where('type','<','4')->count();
        $res = Order::where('user_id',Auth::id())->with(['plug'=>function($query){
            $query->select('plugs.id','plugs.title','plugs.gold','plugs.version','plugs.game_version','plugs.plug_id');
        }])->with(['score'=>function($query){
            $query->where('scores.user_id',Auth::id());
        }])->where('type','<','4')->skip(($page-1)*$size)->take($size)->get();

        return ['count'=>$count , 'res'=>$res];
    }

    /**
     * @param $page
     * @param $size
     * 我收藏的插件
     */
    public function orders_collect($page, $size)
    {
        $count = Plug::leftJoin('collect_plugs','plugs.plug_id','collect_plugs.plug_id')->where('is_new',1)->where('collect_plugs.user_id',Auth::id())->count();
        $res = Plug::leftJoin('collect_plugs','plugs.plug_id','collect_plugs.plug_id')->where('is_new',1)->where('collect_plugs.user_id',Auth::id())->select('plugs.id','plugs.title','plugs.gold','plugs.version','plugs.game_version','plugs.plug_id')->skip(($page-1)*$size)->take($size)->get();
        return ['res'=>$res,'count'=>$count];
    }

    /**
     * @param $page
     * @param $size
     * 我上传的插件
     */
    public function orders_upload($page, $size)
    {
        $count = Plug::where('user_id',Auth::id())->where('is_new',1)->count();
        $res = Plug::where('user_id',Auth::id())->with(['historys'=>function($query){
            $query->where('is_new',0)->select('plugs.id','plugs.title','plugs.gold','plugs.version','plugs.game_version','plugs.plug_id','plugs.is_check')->latest();
        }])->where('is_new',1)->select('plugs.id','plugs.title','plugs.gold','plugs.version','plugs.game_version','plugs.plug_id','plugs.is_check')->orderBy('created_at','desc')->skip(($page-1)*$size)->take($size)->get();
        return ['count'=>$count , 'res'=>$res];
    }


    /**
     * @param $page
     * @param $size
     * 充值记录
     */
    public function get_orders_history($page, $size)
    {
        $count = Recharge::where('user_id',Auth::id())->where('status',9)->count();
        $res = Recharge::where('user_id',Auth::id())->where('status',9)->skip(($page-1)*$size)->take($size)->orderBy('created_at','desc')->get();
        return ['count'=>$count , 'res'=>$res];
    }

    public function send_mail()
    {
        // put in session
        if (Cache::has(Auth::id()."_send_mail")) {
            return ['sta'=>0 , 'msg'=>'邮件发送失败,请等待'.(60 - time() + Cache::get(Auth::id()."_send_mail")).'s后再次发送' , 'timeOut'=> 60 - time() + Cache::get(Auth::id()."_send_mail")];
        }
        Cache::forget(Auth::id()."_send_mail");
        Cache::add(Auth::id()."_send_mail", time(), 1);
        User::where('id',Auth::id())->update([
            'token' => str_random(60)
        ]);
        $user = User::find(Auth::id());
        $user->notify(new \App\Notifications\UserCreated($user));
        return ['sta'=>1 , 'msg'=>'邮件发送成功'];
    }

    public function send_email($email)
    {
        // put in session
        if (Cache::has(Auth::id()."_send_code_email")) {
            return ['sta'=>0 , 'msg'=>'邮件发送失败,请等待'.(60 - time() + Cache::get(Auth::id()."_send_msg")).'s后再次发送' , 'timeOut'=> 60 - time() + Cache::get(Auth::id()."_send_code_email")];
        }
        $code = rand(100000,999999);
        Mail::to($email)->send(new \App\Mail\sendCodeToUser($code));
        Cache::forget(Auth::id()."_send_code_email");
        Cache::forget(Auth::id()."_send_email_code");
        Cache::add(Auth::id()."_send_email_code", json_encode(['code'=>$code , 'email'=> $email]), 10);
        Cache::add(Auth::id()."_send_code_email", time(), 1);
        return ['sta'=>1 , 'msg'=>'邮件发送成功'];
    }

    public function send_msg($tel , $type, $alipay = 0)
    {
        // put in session
        if (Cache::has(Auth::id()."_send_msg_".$type)) {
            return ['sta'=>0 , 'msg'=>'短信发送失败,请等待'.(60 - time() + Cache::get(Auth::id()."_send_msg_".$type)).'s后再次发送' , 'timeOut'=> 60 - time() + Cache::get(Auth::id()."_send_msg_".$type)];
        }
        $code = rand(100000,999999);
        $is_true = send_msg($code,$tel,config('my.msg_template.'.$type));
        if($is_true['success']){
            Cache::forget(Auth::id()."_send_msg_".$type);
            Cache::forget(Auth::id()."_send_msg_code_".$type);
            Cache::add(Auth::id()."_send_msg_code_".$type, json_encode(['code'=>$code , 'tel'=> $tel , 'alipay'=>$alipay]), 10);
            Cache::add(Auth::id()."_send_msg_".$type, time(), 1);
            return ['sta'=>1 , 'msg'=>'短信发送成功'];
        }
        return ['sta'=>0 , 'msg'=>'短信发送失败'];
    }


    public function update_tel(Request $request)
    {
        $cache_code = Auth::id()."_send_msg_code_1";
        if (!Cache::has($cache_code)) {
            return ['sta'=>0 , 'msg'=>'验证码已失效'];
        }
        $cache = Cache::get($cache_code);
        $cache = json_decode($cache,true);
        if($request->code != $cache['code'] || $request->tel != $cache['tel']){
            return ['sta'=>0 , 'msg'=>'验证码错误'];
        }
        $user = User::where('id',Auth::id())->update([
            'tel' => $request->tel
        ]);
        if($user){
            Cache::forget($cache_code);
            return ['sta'=>1 , 'msg'=>'更新成功' ,'info'=>User::find(Auth::id())];
        }
        return ['sta'=>0 , 'msg'=>'更新失败'];
    }

    public function update_email(Request $request)
    {
        if (!Cache::has(Auth::id()."_send_email_code")) {
            return ['sta'=>0 , 'msg'=>'验证码已失效'];
        }
        $cache = Cache::get(Auth::id()."_send_email_code");
        $cache = json_decode($cache,true);
        if($request->code != $cache['code'] || $request->email != $cache['email']){
            return ['sta'=>0 , 'msg'=>'验证码错误'];
        }
        $user = User::where('id',Auth::id())->update([
            'email' => $request->email
        ]);
        if($user){
            Cache::forget(Auth::id()."_send_email_code");
            return ['sta'=>1 , 'msg'=>'更新成功' ,'info'=>User::find(Auth::id())];
        }
        return ['sta'=>0 , 'msg'=>'更新失败'];
    }

    public function update_Alipay(Request $request)
    {
        $cache_code = Auth::id()."_send_msg_code_3";
        if (!Cache::has($cache_code)) {
            return ['sta'=>0 , 'msg'=>'验证码已失效'];
        }
        $cache = Cache::get($cache_code);
        $cache = json_decode($cache,true);
        if($request->code != $cache['code'] || Auth::user()->tel != $cache['tel'] || ($cache['alipay'] != 0 && $request->Alipay != $cache['alipay'])){
            return ['sta'=>0 , 'msg'=>'验证码错误'];
        }
        $user = User::where('id',Auth::id())->update([
            'alipay' => $request->Alipay,
            'alipay_name' => $request->alipayName
        ]);
        if($user){
            Cache::forget($cache_code);
            return ['sta'=>1 , 'msg'=>'更新成功' ,'info'=>User::find(Auth::id())];
        }
        return ['sta'=>0 , 'msg'=>'更新失败'];
    }

    /**
     * @param Request $request
     * password
     */
    public function check_password(Request $request)
    {
        $user = User::find(Auth::id());
        if(Hash::check($request->password, $user->password)) {
            return ['sta'=> 1 ,'msg'=>'ok'];
        }
        return ['sta'=> 0 ,'msg'=>'原密码错误' ];
    }

    /**
     * @param Request $request
     * password
     */
    public function update_password(Request $request)
    {
        $user = User::find(Auth::id());
        $user = $user->update([
            'password' => Hash::make($request->password)
        ]);
        if($user){
            return ['sta'=> 1 ,'msg'=>'密码重置成功'];
        }
        return ['sta'=> 0 ,'msg'=>'密码重置失败' ];
    }

    public function get_user_lv()
    {
        $recharge = Recharge::where('user_id',Auth::id())->where('status',9)->sum('recharge_amount');
        $type = Lv::get();
        if(count($type) === 0){
            $type = collect([['name'=>'新手','money'=>0,'giving'=>0]]);
        }
        $lv = [];
        foreach ($type as $k => $v){
            if( $recharge >= $v->money){
                $lv = $type[$k];
            }
        }
        return ['sta'=>1 , 'info'=>$lv ? $lv : collect([['name'=>'新手','money'=>0,'giving'=>0]])];
    }

    public function check_withdraw()
    {
        $color = Auth::user()->camp === 1 ? '#266ec1' : '#d13030';
        if(Auth::user()->gold < 2000){
            return ['sta'=>0 , 'msg'=>'当金币数量等同于 <span style="color: '.$color.';font-size: 20px;font-weight: bold">200</span> 人民币时可申请提现'];
        }else if((time() - strtotime(Auth::user()->created_at)) < (30*60*60*24)){
            return ['sta'=>0 , 'msg'=>'新注册用户 <span style="color: '.$color.';font-size: 20px;font-weight: bold">30</span> 日内不能提现'];
        }else if(!Auth::user()->alipay){
            return ['sta'=>0 , 'msg'=>'请先绑定支付宝'];
        }

        return ['sta'=>1];
    }


    public function user_list(Request $request,$page,$size)
    {
        $where = User::when($request->search['name'] != null, function ($query) use ($request) {
            return $query->where('name', 'like' , '%'.$request->search['name'].'%');
        })
            ->when($request->search['camp'] != null, function ($query) use ($request) {
                return $query->where('camp', $request->search['camp']);
            })
            ->when($request->search['status'] != null, function ($query) use ($request) {
                return $query->where('status', $request->search['status']);
            })
            ->when($request->search['is_admin'] != null, function ($query) use ($request) {
                return $query->where('is_admin', $request->search['is_admin']);
            })
            ->when($request->search['is_active'] != null, function ($query) use ($request) {
                return $query->where('is_active', $request->search['is_active']);
            });

        $count = $where->count();
        $users = $where->with(['plugs'=>function($query){
            $query->select('plugs.user_id');
        }])->skip(($page-1)*$size)->take($size)->get();

        return ['sta'=>1, 'count'=>$count, 'users'=>$users];
    }

    public function change_status($id, $v)
    {
        $tag = User::where('id',$id)->update([
            'status' =>$v
        ]);
        if($tag)
            return ['sta'=>1, 'msg'=>'更新成功'];
        return ['sta'=>0, 'msg'=>'更新失败'];
    }


    public function change_is_admin($id, $v)
    {
        $tag = User::where('id',$id)->update([
            'is_admin' =>$v
        ]);
        if($tag)
            return ['sta'=>1, 'msg'=>'更新成功'];
        return ['sta'=>0, 'msg'=>'更新失败'];
    }

    public function user_name(Request $request)
    {
        $username = User::where('id','!=',$request->id)->where('nickname',$request->name)->count();
        $is_wg = Tool::where('name','nickname')->where('value',$request->name)->first();
        if($username > 0 || $is_wg)
            return ['sta'=>0,'msg'=>'用户名已存在或违规'];
        return ['sta'=>1];
    }

    public function user_email(Request $request)
    {
        $username = User::where('id','!=',$request->id)->where('email',$request->email)->count();
        if($username > 0)
            return ['sta'=>0,'msg'=>'邮箱已存在'];
        return ['sta'=>1];
    }

    public function user_tel(Request $request)
    {
        $username = User::where('id','!=',$request->id)->where('tel',$request->tel)->count();
        if($username > 0)
            return ['sta'=>0,'msg'=>'手机号码已存在'];
        return ['sta'=>1];
    }

    public function admin_update(Request $request, $id)
    {
        $user = User::where('id',$id)->update([
            'name' => $request->data['name'],
            'email' =>  $request->data['email'],
            'tel' =>  $request->data['tel'] == '' ? 0 : $request->data['tel'],
            'camp' =>  $request->data['camp'],
            'avatar' =>  $request->data['avatar'],
        ]);
        if($user)
            return ['sta'=>1 , 'msg'=>'信息更新成功'];
        return ['sta'=>0 , 'msg'=>'信息更新失败'];
    }

    public function check_nickname($name , $user_id = 0)
    {
        $user_id = $user_id === 0 ? Auth::id() : $user_id;
        $count = User::where('id','!=',$user_id)->where('nickname',$name)->count();
        if($count > 0){
            return 0;
        }
        $is_wg = Tool::where('name','nickname')->where('value',$name)->first();
        if($is_wg){
            return 0;
        }
        return 1;
    }

    public function get_withdraws($page, $size)
    {
        $count = Withdraws::where('user_id',Auth::id())->count();
        $res = Withdraws::where('user_id',Auth::id())->skip(($page-1)*$size)->take($size)->orderBy('created_at','desc')->get();
        return ['count'=>$count , 'res'=>$res];
    }


    public function password_email(Request $request)
    {
        $messages = [
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '验证码输入错误'
        ];
        $this->validate($request, ['email' => 'required|email' , 'name' => 'required|string' , 'captcha'=>'required|captcha'],$messages);

        $name = User::where('email',$request->email)->value('name');
        if(!$name){
            return back()->withInput($request->input())->withErrors(['email'=>'此邮箱不存在']);
        }else{
            if($name != $request->name){
                return back()->withInput($request->input())->withErrors(['name'=>'用户名不匹配']);
            }
        }

        //xieru shujuku
        $token = str_random(60);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $user = User::where('email',$request->email)->first();
        $user->notify(new SendPasswordRestMail($token));
        $request->session()->flash('status', '邮件发送成功');
        return back();
    }

    public function send_rest_sms(Request $request)
    {
        if(!$request->name){
            return ['sta'=>0 , 'msg'=>'用户名不能为空'];
        }

        if(!$request->tel){
            return ['sta'=>0 , 'msg'=>'手机号码不能为空'];
        }

        $name = User::where('tel',$request->tel)->value('name');
        if(!$name){
            return ['sta'=>0 , 'msg'=>'手机号码不存在'];
        }else{
            if($name != $request->name){
                return ['sta'=>0 , 'msg'=>'用户名不匹配'];
            }
        }

        //send——code

        $tel = $request->tel;
        $type = 2;
        if (Cache::has($tel."_send_msg_".$type)) {
            return ['sta'=>0 , 'msg'=>'短信发送失败,请等待'.(60 - time() + Cache::get($tel."_send_msg_".$type)).'s后再次发送' , 'timeOut'=> 60 - time() + Cache::get($tel."_send_msg_".$type)];
        }
        $code = rand(100000,999999);
        $is_true = send_msg($code,$tel,config('my.msg_template.'.$type));
        if($is_true['success']){
            Cache::forget($tel."_send_msg_".$type);
            Cache::forget($tel."_send_msg_code_".$type);
            Cache::add($tel."_send_msg_code_".$type, json_encode(['code'=>$code , 'tel'=> $tel]), 10);
            Cache::add($tel."_send_msg_".$type, time(), 1);
            return ['sta'=>1 , 'msg'=>'短信发送成功'];
        }
        return ['sta'=>0 , 'msg'=>'短信发送失败'];
    }


    public function password_tel(Request $request)
    {
        $messages = [
            'tel.required' => '手机号码不能为空',
        ];

        $this->validate($request, ['tel' => 'required' , 'name' => 'required|string'],$messages);

        $name = User::where('tel',$request->tel)->value('name');

        if(!$name){
            return back()->withInput($request->input())->withErrors(['tel'=>'手机号码不存在']);
        }else{
            if($name != $request->name){
                return back()->withInput($request->input())->withErrors(['name'=>'用户名不匹配']);
            }
        }

        //check_code
        $tel = $request->tel;
        $type = 2;
        if (!Cache::has($tel."_send_msg_".$type)) {
            return back()->withInput($request->input())->withErrors(['code'=>'验证码已失效']);
        }

        $code = Cache::get($tel."_send_msg_code_".$type);
        $code = json_decode($code,true);
        if($code['code'] == $request->code && $code['tel'] == $request->tel){
            // ok
            $token = str_random(60);
            DB::table('password_resets')->insert([
                'email' => $request->tel,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            return redirect(route('password.reset', $token));
        }else{
            return back()->withInput($request->input())->withErrors(['code'=>'验证码错误']);
        }
    }

    public function request_sms(Request $request)
    {
        $messages = [
            'password.is_pass' => '大小写字母+数字，不少于8位',
        ];
        $this->validate($request, ['token' => 'required' , 'password' => 'required|string|min:8|is_pass|confirmed'],$messages);

        $email = DB::table('password_resets')->where('token',$request->token)->latest()->value('email');

        $user = User::where('email',$email)->orWhere('tel',$email)->first();

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        Auth::guard()->login($user);

        DB::table('password_resets')->where('email', $email)->delete();

        return redirect('/');
    }

}
