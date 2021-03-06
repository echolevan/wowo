<?php

namespace App\Http\Controllers;

use Yansongda\Pay\Pay;
use App\Recharge;
use App\User;
use EchoBool\AlipayLaravel\Facades\Alipay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayController extends Controller
{
    //

    protected $config = [];

    public function __construct()
    {
        $this->config['alipay'] =  config('pay.alipay');
        $this->config['wechat'] =  config('pay.wechat');
    }

    public function wechat_pay($order)
    {
        $config_biz = [
            'out_trade_no' => $order->out_trade_no,
            'total_fee' => $order->recharge_amount * 100, // **单位：分**
            'body' => '充值'.$order->recharge_gold.'金币',
            'spbill_create_ip' => request()->getClientIp(),
            'product_id' =>  $order->id,
        ];

        $pay = new Pay($this->config);

        return $pay->driver('wechat')->gateway('scan')->pay($config_biz);
    }

    public function find_wechat($out_trade_no)
    {
        $pay = new Pay($this->config);
        $res =  $pay->driver('wechat')->gateway('scan')->find($out_trade_no);
        if($res['trade_state'] == 'SUCCESS'){
            $recharge = Recharge::where('out_trade_no',$out_trade_no)->first();
            if($recharge->status === 9){
                Log::info('success');
                return ['sta'=>1 ,'info'=> User::find(Auth::id())];
            }else {
                DB::beginTransaction();
                $gold = User::where('id', $recharge->user_id)->value('gold');
                try {
                    Recharge::where('out_trade_no', $out_trade_no)->where('status', '!=', '9')->update([
                        'status' => 9
                    ]);
                    User::where('id', $recharge->user_id)->update([
                        'gold' => $gold + $recharge->recharge_amount * 10 + $recharge->giving_gold
                    ]);
                    Log::info('add_success');
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error(json_encode([$recharge, $recharge->user_id]));
                    echo "fail";
                }
            }
            return ['sta'=>0 , 'info' => User::find(Auth::id())];
        }
    }

    public function wechat_notify(Request $request)
    {
        $pay = new Pay($this->config);
        $verify = $pay->driver('wechat')->gateway('scan')->verify($request->getContent());

        if ($verify) {
            $out_trade_no = $verify['out_trade_no'];
            $recharge = Recharge::where('out_trade_no',$out_trade_no)->first();
            if($recharge->status === 9){
                Log::info('success');
                echo "success";
                exit;
            }else{
                DB::beginTransaction();
                $gold = User::where('id',$recharge->user_id)->value('gold');
                try{
                    Recharge::where('out_trade_no',$out_trade_no)->where('status','!=','9')->update([
                        'status'=>9
                    ]);
                    User::where('id',$recharge->user_id)->update([
                        'gold' => $gold + $recharge->recharge_amount*10 + $recharge->giving_gold
                    ]);
                    Log::info('add_success');
                    DB::commit();
                }catch(\Exception $e){
                    DB::rollBack();
                    Log::error(json_encode([$recharge, $recharge->user_id]));
                    echo "fail";
                }
            }
        } else {
            Log::error("收到异步通知\r\n", FILE_APPEND);
        }
        echo "success";
    }

    public function alipay($order)
    {
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order->out_trade_no;
        //订单名称，必填
        $subject = '充值'.$order->recharge_gold.'金币';
        //付款金额，必填
//        $total_amount = $order->recharge_amount;
        $total_amount = $order->recharge_amount;
        //商品描述，可空
        $body = '充值金币';
        $customData = json_encode(['recharge_gold' => $order->recharge_gold, 'user_id' => $order->user_id]);//自定义参数
        $response = Alipay::tradePagePay($subject, $body, $out_trade_no, $total_amount, $customData);
        //输出表单
        return $response;
    }

    public function tradePayQuery($out_trade_no)
    {
        //商户订单号
        $response = Alipay::tradePayQuery($out_trade_no);
//        TRADE_SUCCESS
        if(isset($response->trade_status) && $response->trade_status == 'TRADE_SUCCESS'){
            $recharge = Recharge::where('out_trade_no',$out_trade_no)->first();
            if($recharge->status === 9){
                Log::info('success');
                return ['sta'=>1 ,'info'=> User::find($recharge->user_id)];
            }else{
                DB::beginTransaction();
                $gold = User::where('id',$recharge->user_id)->value('gold');
                try{
                    Recharge::where('out_trade_no',$out_trade_no)->where('status','!=','9')->update([
                        'status'=>9
                    ]);
                    User::where('id',$recharge->user_id)->update([
                        'gold' => $gold + $recharge->recharge_amount*10 + $recharge->giving_gold
                    ]);
                    Log::info('add_success');
                    DB::commit();
                }catch(\Exception $e){
                    DB::rollBack();
                    Log::error(json_encode([$recharge, $recharge->user_id]));
                    echo "fail";
                }
            }
            return ['sta'=>1 ,'info'=> User::find(Auth::id())];
        }
        return 0;
    }

    public function alipay_notify(Request $request)
    {
        $result = Alipay::notify($_POST);
        Log::info('notify_begin');
        Log::info(print_r($result));
        Log::info($_POST);
        Log::info('notify_end');
        /* 实际验证过程建议商户添加以下校验。
       1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
       2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
       3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
       4、验证app_id是否为该商户本身。
       */
        if ($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];

            if($trade_status == 'TRADE_SUCCESS'){
                $recharge = Recharge::where('out_trade_no',$out_trade_no)->first();
                if($recharge->status === 9){
                    Log::info('success');
                    echo "success";
                    exit;
                }else{
                    DB::beginTransaction();
                    $gold = User::where('id',$recharge->user_id)->value('gold');
                    try{
                        Recharge::where('out_trade_no',$out_trade_no)->where('status','!=','9')->update([
                            'status'=>9
                        ]);
                        User::where('id',$recharge->user_id)->update([
                            'gold' => $gold + $recharge->recharge_amount*10 + $recharge->giving_gold
                        ]);
                        Log::info('add_success');
                        DB::commit();
                    }catch(\Exception $e){
                        DB::rollBack();
                        Log::error(json_encode([$recharge, $recharge->user_id]));
                        echo "fail";
                    }
                }
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";    //请不要修改或删除
        } else {
            //验证失败
            echo "fail";
        }
    }

    public function return_url()
    {
        $result = Alipay::notify($_GET);
        /* 实际验证过程建议商户添加以下校验。
            1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
            2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
            3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
            4、验证app_id是否为该商户本身。
         */
        if ($result) {//验证成功
            //商户订单号
            $out_trade_no = (string)$_GET['out_trade_no'];
            $recharge = Recharge::where('out_trade_no',$out_trade_no)->first();
            if($recharge->status === 9){
                return redirect('/#/userInfo/pay');
            }
            DB::beginTransaction();
            try{
                Recharge::where('out_trade_no',$out_trade_no)->where('status','!=','9')->update([
                    'status'=>9
                ]);
                User::where('id',Auth::id())->update([
                    'gold' => Auth::user()->gold + $recharge->recharge_amount*10 + $recharge->giving_gold
                ]);
                DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                Log::error(json_encode([$recharge, Auth::id()]));
                return ['sta'=>0 , 'msg'=>'充值失败，但付款成功，请联系客服'];
            }
            return redirect('/#/userInfo/pay');
        } else {
            //验证失败
            echo "支付失败";
        }
    }
}
