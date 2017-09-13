<?php

namespace App\Http\Controllers;

use App\Plug;
use App\Recharge;
use App\User;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    //
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
            })
            ->oldest()->groupBy('date')->get();

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
}
