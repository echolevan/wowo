<?php

namespace App\Http\Controllers;

use App\Lv;
use App\Plug;
use App\Recharge;
use App\Statistic;
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

        $r = Recharge::where('status',9)->sum('recharge_amount');
        $w = Withdraws::where('status',9)->sum('money');
        $res['recharge'] = $this->doFormatMoney((string)$r);
        $res['draws'] = $this->doFormatMoney((string)$w);
        $res['total'] = $this->doFormatMoney((string)($r - $w));

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

    function doFormatMoney($money){
        $tmp_money = strrev($money);
        $format_money = "";
        for($i = 3;$i<strlen($money);$i+=3){
            $format_money .= substr($tmp_money,0,3).",";
            $tmp_money = substr($tmp_money,3);
        }
        $format_money .=$tmp_money;
        $format_money = strrev($format_money);
        return $format_money;
    }

    function sys_linux()

    {

        // CPU

        if (false === ($str = @file("/proc/cpuinfo"))) return false;

        $str = implode("", $str);

        @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);

        @preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);

        @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);

        @preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $bogomips);

        if (false !== is_array($model[1]))

        {

            $res['cpu']['num'] = sizeof($model[1]);
            /*

            for($i = 0; $i < $res['cpu']['num']; $i++)

            {

                $res['cpu']['model'][] = $model[1][$i].'&nbsp;('.$mhz[1][$i].')';

                $res['cpu']['mhz'][] = $mhz[1][$i];

                $res['cpu']['cache'][] = $cache[1][$i];

                $res['cpu']['bogomips'][] = $bogomips[1][$i];

            }*/
            if($res['cpu']['num']==1)
                $x1 = '';
            else
                $x1 = ' ×'.$res['cpu']['num'];
            $mhz[1][0] = ' | 频率:'.$mhz[1][0];
            $cache[1][0] = ' | 二级缓存:'.$cache[1][0];
            $bogomips[1][0] = ' | Bogomips:'.$bogomips[1][0];
            $res['cpu']['model'][] = $model[1][0].$mhz[1][0].$cache[1][0].$bogomips[1][0].$x1;

            if (false !== is_array($res['cpu']['model'])) $res['cpu']['model'] = implode("<br />", $res['cpu']['model']);

            if (false !== is_array($res['cpu']['mhz'])) $res['cpu']['mhz'] = implode("<br />", $res['cpu']['mhz']);

            if (false !== is_array($res['cpu']['cache'])) $res['cpu']['cache'] = implode("<br />", $res['cpu']['cache']);

            if (false !== is_array($res['cpu']['bogomips'])) $res['cpu']['bogomips'] = implode("<br />", $res['cpu']['bogomips']);

        }


        // NETWORK


        // UPTIME

        if (false === ($str = @file("/proc/uptime"))) return false;

        $str = explode(" ", implode("", $str));

        $str = trim($str[0]);

        $min = $str / 60;

        $hours = $min / 60;

        $days = floor($hours / 24);

        $hours = floor($hours - ($days * 24));

        $min = floor($min - ($days * 60 * 24) - ($hours * 60));

        if ($days !== 0) $res['uptime'] = '<span  style="color: #CC0000">'.$days.'</span>'."天";

        if ($hours !== 0) $res['uptime'] .= '<span  style="color: #CC0000">'.$hours.'</span>'."小时";

        $res['uptime'] .= '<span  style="color: #CC0000">'.$min.'</span>'."分钟";


        // MEMORY

        if (false === ($str = @file("/proc/meminfo"))) return false;

        $str = implode("", $str);

        preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);
        preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buffers);


        $res['memTotal'] = round($buf[1][0]/1024, 2);

        $res['memFree'] = round($buf[2][0]/1024, 2);

        $res['memBuffers'] = round($buffers[1][0]/1024, 2);
        $res['memCached'] = round($buf[3][0]/1024, 2);

        $res['memUsed'] = $res['memTotal']-$res['memFree'];

        $res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;


        $res['memRealUsed'] = $res['memTotal'] - $res['memFree'] - $res['memCached'] - $res['memBuffers']; //真实内存使用
        $res['memRealFree'] = $res['memTotal'] - $res['memRealUsed']; //真实空闲
        $res['memRealPercent'] = (floatval($res['memTotal'])!=0)?round($res['memRealUsed']/$res['memTotal']*100,2):0; //真实内存使用率

        $res['memCachedPercent'] = (floatval($res['memCached'])!=0)?round($res['memCached']/$res['memTotal']*100,2):0; //Cached内存使用率

        $res['swapTotal'] = round($buf[4][0]/1024, 2);

        $res['swapFree'] = round($buf[5][0]/1024, 2);

        $res['swapUsed'] = round($res['swapTotal']-$res['swapFree'], 2);

        $res['swapPercent'] = (floatval($res['swapTotal'])!=0)?round($res['swapUsed']/$res['swapTotal']*100,2):0;


        // LOAD AVG

        if (false === ($str = @file("/proc/loadavg"))) return false;

        $str = explode(" ", implode("", $str));

        $str = array_chunk($str, 4);

        $res['loadAvg'] = implode(" ", $str[0]);


        return $res;

    }

    public function service_info()
    {
        error_reporting(0); //抑制所有错误信息
        $dt = round(@disk_total_space(".")/(1024*1024*1024),3); //总
        $df = round(@disk_free_space(".")/(1024*1024*1024),3); //可用
        $du = $dt-$df; //已用
        $hdPercent = (floatval($dt)!=0)?round($du/$dt*100,2):0; //使用率

        $sysInfo = $this->sys_linux();
//    $sysinfo = GetWMI($wmi,"Win32_OperatingSystem", array('LastBootUpTime','TotalVisibleMemorySize','FreePhysicalMemory','Caption','CSDVersion','SerialNumber','InstallDate'));

        $tmp = array(

            'memTotal', 'memUsed', 'memFree', 'memPercent',

            'memCached', 'memRealPercent',

            'swapTotal', 'swapUsed', 'swapFree', 'swapPercent'

        );

        foreach ($tmp AS $v) {

            $sysInfo[$v] = $sysInfo[$v] ? $sysInfo[$v] : 0;

        }

        $sysInfo['time'] = date('Y-m-d H:i:s');
        return ['dt'=>$dt , 'df'=>$df , 'du'=>$du , 'hdp'=>$hdPercent , 'sysinfo' => $sysInfo];
    }

    function getNetInfo(){
        error_reporting(0); //抑制所有错误信息
        $strs = @file("/proc/net/dev");
        for ($i = 2; $i < count($strs); $i++ )
        {
            preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
            $NetOutSpeed[$i] = $info[10][0];
            $NetInputSpeed[$i] = $info[2][0];
            $NetInput[$i] = $this->formatsize($info[2][0]);
            $NetOut[$i]  = $this->formatsize($info[10][0]);
        }
        return ['info'=>$info,'NetOutSpeed' => $NetOutSpeed, 'NetInputSpeed' => $NetInputSpeed,'NetInput' => $NetInput,'NetOut'=>$NetOut];
    }

    //单位转换
    function formatsize($size)
    {
        error_reporting(0); //抑制所有错误信息
        $danwei=array(' B ',' K ',' M ',' G ',' T ');
        $allsize=array();
        $i=0;

        for($i = 0; $i <5; $i++)
        {
            if(floor($size/pow(1024,$i))==0){break;}
        }

        for($l = $i-1; $l >=0; $l--)
        {
            $allsize1[$l]=floor($size/pow(1024,$l));
            $allsize[$l]=$allsize1[$l]-$allsize1[$l+1]*1024;
        }

        $len=count($allsize);

        $fsize = '';
        for($j = $len-1; $j >=0; $j--)
        {
            $fsize=$fsize.$allsize[$j].$danwei[$j];
        }
        return $fsize;
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

    function getAgentInfo(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $brower = array(
            'Maxthon' => 1,
			'Firefox' => 2,
            'Opera' => 3,
            'MSIE' => 4,
            'Edge' => 5,
            'Safari' => 6,
            'Chrome' => 7,
            'MicroMessenger' => 8
        );
        $system = array(
            'Windows' => 1,
            'Windows Phone' => 2,
            'Android' => 3,
            'Macintosh'=> 4,
            'iPhone' => 5,
            'iPad' => 6,
            'Linux'=>7
        );
        $system_type = '其他';//未知
        $browser_type = '其他';//未知
        foreach($brower as $bro => $val){
            if(stripos($agent, $bro) !== false){
                $browser_type = $bro;
                break;
            }
        }
        foreach($system as $sys => $val){
            if(stripos($agent, $sys) !== false){
                if($sys === 'Macintosh')
                    $sys = 'Mac';
                else if($sys === 'linux')
                    $sys = 'Linux';
                
                $system_type = $sys;
                break;
            }
        }
        $sys_db = Statistic::where('name',$system_type)->where('type',2)->first();
        if($sys_db){
            $sys_db->increment('num');
        }else{
            Statistic::create([
                'name' => $system_type,
                'num' => 1,
                'type' => 2
            ]);
        }

        $bro_db = Statistic::where('name',$browser_type)->where('type',1)->first();
        if($bro_db){
            $bro_db->increment('num');
        }else{
            Statistic::create([
                'name' => $browser_type,
                'num' => 1,
                'type' => 1
            ]);
        }
        return array('sys' => $system_type, 'bro' => $browser_type);
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
        $columns = ['时间' , '资源发布量'];


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
            $data[$num]['资源发布量'] = count($v);
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
            $data[$num]['时间'] = date('y/m/d',strtotime($v->time));
            $data[$num]['登录人次'] = $v->num;
            $num++;
        }
        return ['columns' => $columns , 'data' => $data];

    }

    public function draws(Request $request)
    {
        $columns = ['时间' , '提现次数' , '提现金额' ];

        $s = date('Y-m-d',strtotime($request->time[0]));
        $e = date('Y-m-d',strtotime($request->time[1]) +  60*60*24);

        $res = Withdraws::select(DB::raw("date_format(created_at , '%y/%m/%d') as date , sum(money) as sum , count(*) as count"))
            ->when($request->time != '' && $request->time[0] , function($query) use ($e, $s) {
            return $query->where('created_at' ,'>=' ,  $s)->where('created_at' ,'<=' ,  $e);
        })->where('status',9)->orderBy('date')->groupBy('date')->get();

        $data = [];
        $num = 0;
        foreach ($res as $k => $v){
            $data[$num]['时间'] = $v->date;
            $data[$num]['提现次数'] = $v->count;
            $data[$num]['提现金额'] = $v->sum;
            $num++;
        }
        return ['columns' => $columns , 'data' => $data];
    }


    public function sys_charts()
    {
        $columns = ['类型' , '数量'];
        $res = Statistic::select('name','num')->where('type',2)->get();
        $data = [];
        $num = 0;
        foreach ($res as $k => $v){
            $data[$num]['类型'] = $v->name;
            $data[$num]['数量'] = $v->num;
            $num++;
        }
        return ['columns' => $columns , 'data' => $data];
    }

    public function bro_charts()
    {
        $columns = ['类型' , '数量'];
        $res = Statistic::select('name','num')->where('type',1)->get();
        $num = 0;
        $data = [];
        foreach ($res as $k => $v){
            $data[$num]['类型'] = $v->name;
            $data[$num]['数量'] = $v->num;
            $num++;
        }
        return ['columns' => $columns , 'data' => $data];
    }
}
