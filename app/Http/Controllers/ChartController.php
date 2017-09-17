<?php

namespace App\Http\Controllers;

use App\Lv;
use App\Plug;
use App\Recharge;
use App\User;
use App\Order;
use App\Withdraws;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    //

    public function index()
    {
        $user = User::select('id','camp')->get();
        $res['user_count'] = count($user);
        $user = $user->groupBy('camp');
        $res['user_lm'] = isset($user[1]) ? count($user[1]) : 0;
        $res['user_bl'] = isset($user[2]) ? count($user[2]) : 0;

        $plug = Plug::select('id','type')->get();
        $res['plug_count'] = count($plug);
        $plug = $plug->groupBy('type');
        $res['plug_wa'] = isset($plug[1]) ? count($plug[1]) : 0;
        $res['plug_tmw'] = isset($plug[2]) ? count($plug[2]) : 0;
        $res['plug_plug'] = isset($plug[3]) ? count($plug[3]) : 0;

        $res['recharge'] = Recharge::where('status',9)->sum('recharge_amount');
        $res['draws'] = Withdraws::where('status',9)->sum('money');

        $lv = Lv::orderBy('money')->get();
        $user_lv = Recharge::select(DB::raw("sum(recharge_amount) as sum , user_id"))->where('status',9)->groupBy('user_id')->get();
        if(count($lv) === 0){
            $res['lv'] = '';
        }else if(count($lv) === 1){
            $res['lv'][1] = count($user_lv);
        }else{
            for ($i = 0 ; $i < count($lv) ; $i ++){
                $new_lv[$i][0] = $lv[$i]->money;
                $new_lv[$i][1] = isset($lv[$i + 1]->money) ? $lv[$i + 1]->money : 9999999999999;
            }

            foreach ($user_lv as $k => $v){
                foreach ($new_lv as $kk => $vv){
                    if($v->sum >= $vv[0] && $v->sum <= $vv[1]){
                        $res['lv'][$kk][] = $v->user_id;
                    }

                }
            }
        }
        $res['ff'] = count($user_lv);

        $res['service_info'] = $this->service_info();
        return ['res'=>$res , 'lv'=>$lv];
    }


    public function service_info()
    {
        $info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            '主机名'=>$_SERVER['SERVER_NAME'],
            'WEB服务端口'=>$_SERVER['SERVER_PORT'],
            '网站文档目录'=>$_SERVER["DOCUMENT_ROOT"],
            '浏览器信息'=>substr($_SERVER['HTTP_USER_AGENT'], 0, 40),
            '通信协议'=>$_SERVER['SERVER_PROTOCOL'],
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '用户的IP地址'=>$_SERVER['REMOTE_ADDR'],
            '总共空间' => round((disk_total_space(".")/(1024*1024)),2).'M',
            '剩余空间'=>round((disk_free_space(".")/(1024*1024)),2).'M',
        );
        return $info;
    }

    function GetBrowser(){
        if(!empty($_SERVER['HTTP_USER_AGENT'])){
            $br = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/MSIE/i',$br)) {
                $br = 'MSIE';
            }elseif (preg_match('/Firefox/i',$br)) {
                $br = 'Firefox';
            }elseif (preg_match('/Chrome/i',$br)) {
                $br = 'Chrome';
            }elseif (preg_match('/Safari/i',$br)) {
                $br = 'Safari';
            }elseif (preg_match('/Opera/i',$br)) {
                $br = 'Opera';
            }else {
                $br = 'Other';
            }
            return $br;
        }else{return "获取浏览器信息失败！";}
    }


    public function userCreated(Request $request)
    {

        $columns = ['时间' , '用户数量'];

        $s = Carbon::createFromTimestamp(strtotime($request->time[0]));
        $e = Carbon::createFromTimestamp(strtotime($request->time[1]) + 60*60*24);

        $res = User::select('created_at')
            ->when($request->time != '' && $request->time[0], function($query) use ($e, $s) {
                return $query->where('created_at' ,'>=' ,  $s)->where('created_at' ,'<=' ,  $e);
            })
            ->oldest()->get();
        $res = $res->groupBy('year_created_at');

        $data = [];
        $num = 0;
        foreach ($res as $k => $v){
            $data[$num]['时间'] = date('y/m/d',strtotime($k));
            $data[$num]['用户数量'] = count($v);
            $num++;
        }

        return ['columns' => $columns , 'data' => $data];

    }

    public function plugCreated(Request $request)
    {
        $columns = ['时间' , '资源数量'];


        $s = Carbon::createFromTimestamp(strtotime($request->time[0]));
        $e = Carbon::createFromTimestamp(strtotime($request->time[1]) + 60*60*24);

        $res = Plug::select('created_at')
            ->when($request->time != '' && $request->time[0], function($query) use ($e, $s) {
                return $query->where('created_at' ,'>=' ,  $s)->where('created_at' ,'<=' ,  $e);
            })
            ->oldest()->get();

        $res = $res->groupBy('created_at');

        $data = [];
        $num = 0;
        foreach ($res as $k => $v){
            $data[$num]['时间'] = date('y/m/d',strtotime($k));
            $data[$num]['资源数量'] = count($v);
            $num++;
        }

        return ['columns' => $columns , 'data' => $data];
    }

    public function orderCharts(Request $request)
    {

        $columns = ['时间' , '资源购买量'];

        $s = Carbon::createFromTimestamp(strtotime($request->time[0]));
        $e = Carbon::createFromTimestamp(strtotime($request->time[1]) + 60*60*24);

        $res = Order::select('created_at')
            ->when($request->time != '' && $request->time[0] , function($query) use ($e, $s) {
                return $query->where('created_at' ,'>=' ,  $s)->where('created_at' ,'<=' ,  $e);
            })
            ->oldest()->get();

        $res = $res->groupBy('year_created_at');

        $data = [];
        $num = 0;
        foreach ($res as $k => $v){
            $data[$num]['时间'] = date('y/m/d',strtotime($k));
            $data[$num]['资源购买量'] = count($v);
            $num++;
        }

        return ['columns' => $columns , 'data' => $data];

    }

    public function recharge(Request $request)
    {
        $columns = ['时间' , '充值次数', '充值金额'];

        $s = Carbon::createFromTimestamp(strtotime($request->time[0]));
        $e = Carbon::createFromTimestamp(strtotime($request->time[1]) + 60*60*24);

        $res = Recharge::select(DB::raw(" date_format (created_at , '%y/%m/%d') as date , sum(recharge_amount) as sum , count(*) as count"))
            ->when($request->time != '' && $request->time[0] , function($query) use ($e, $s) {
                return $query->where('created_at' ,'>=' ,  $s)->where('created_at' ,'<=' ,  $e);
            })->where('status',9)
            ->orderBy('date')->groupBy('date')->get();

        $data = [];
        $num = 0;
        foreach ($res as $k => $v){
            $data[$num]['时间'] = $v->date;
            $data[$num]['充值次数'] = $v->count;
            $data[$num]['充值金额'] = $v->sum;
            $num++;
        }
        return ['columns' => $columns , 'data' => $data];
    }

    public function login(Request $request)
    {
        $columns = ['时间' , '登录人次'];

        $s = date('Y-m-d',strtotime($request->time[0]));
        $e = date('Y-m-d',strtotime($request->time[1]) +  60*60*24);

        $res = DB::table('login')->when($request->time != '' && $request->time[0] , function($query) use ($e, $s) {
            return $query->where('time' ,'>=' ,  $s)->where('time' ,'<=' ,  $e);
        })->orderBy('time')->get();

        $data = [];
        $num = 0;
        foreach ($res as $k => $v){
            $data[$num]['时间'] = $v->time;
            $data[$num]['登录人次'] = $v->num;
            $num++;
        }
        return ['columns' => $columns , 'data' => $data];

    }

    public function draws(Request $request)
    {
        $columns = ['时间' , '提现金额'];

        $s = date('Y-m-d',strtotime($request->time[0]));
        $e = date('Y-m-d',strtotime($request->time[1]) +  60*60*24);

        $res = Withdraws::select(DB::raw("date_format(created_at , '%y/%m/%d') as date , sum(money) as sum"))->when($request->time != '' && $request->time[0] , function($query) use ($e, $s) {
            return $query->where('created_at' ,'>=' ,  $s)->where('created_at' ,'<=' ,  $e);
        })->where('status',9)->orderBy('date')->groupBy('date')->get();

        $data = [];
        $num = 0;
        foreach ($res as $k => $v){
            $data[$num]['时间'] = $v->date;
            $data[$num]['提现金额'] = $v->sum;
            $num++;
        }
        return ['columns' => $columns , 'data' => $data];
    }
}
