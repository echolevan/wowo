<?php

namespace App\Http\Controllers;

use App\Http\Middleware\userLogin;
use App\Lv;
use App\Order;
use App\Plug;
use App\Recharge;
use App\Tool;
use App\User;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
            $arr = explode('@',Auth::user()->email);
            $domain = $arr[1];
            return ['sta' =>'1' , 'info' => Auth::user() , 'email'=>'http://mail.'.$domain , 'tools'=>$tool];
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

        // 如果支付成功


//        $user_info = User::where('id',Auth::id())->first();
        return ['sta'=>1 , 'url'=>route('user.go_to_pay',$rec->id)];

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
                return ['sta'=> 0 ,'msg'=>'30天内不能修改阵营' , 'time'=>$sta['time']];
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
                'update_camp_at' => time(),
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
        if( !Auth::user()->update_camp_at || time() - Auth::user()->update_camp_at > 30*60*60*24 )
            return ['sta'=> 1];
        return ['sta'=> 0 , 'time'=> 30 - floor( (time()- Auth::user()->update_camp_at) / (60*60*24) )];
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
            return ['sta'=>0 , 'msg'=>'邮件发送失败,请等待'.(60 - time() + Cache::get(Auth::id()."_send_mail")).'S后再次发送' , 'timeOut'=> 60 - time() + Cache::get(Auth::id()."_send_mail")];
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
        return ['sta'=> 0 ,'msg'=>'原始密码错误' ];
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
            $type = ['name'=>'新手','money'=>0,'giving'=>0];
        }
        $lv = [];
        foreach ($type as $k => $v){
            if( $recharge >= $v->money){
                $lv = $type[$k];
            }
        }
        return ['sta'=>1 , 'info'=>$lv ? $lv : ['name'=>'新手','money'=>0,'giving'=>0]];
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
            return ['sta'=>0,'msg'=>'用户名已经存在或者违规'];
        return ['sta'=>1];
    }

    public function user_email(Request $request)
    {
        $username = User::where('id','!=',$request->id)->where('email',$request->email)->count();
        if($username > 0)
            return ['sta'=>0,'msg'=>'邮箱重复了'];
        return ['sta'=>1];
    }

    public function user_tel(Request $request)
    {
        $username = User::where('id','!=',$request->id)->where('tel',$request->tel)->count();
        if($username > 0)
            return ['sta'=>0,'msg'=>'手机号重复了'];
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

}
