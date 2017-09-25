<?php

namespace App\Http\Controllers;

use App\User;
use App\Withdraws;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Payment\Common\PayException;
use Payment\Client\Transfer;
use Payment\Config;

class WithdrawController extends Controller
{
    //
    public function index (Request $request)
    {
        if (Auth::user()->gold - $request->drawMoney * 10 < 0) {
            return ['sta' => 0, 'msg' => '提现失败，金币不足'];
        }
        DB::beginTransaction();
        try {
            Withdraws::create([
                'alipay_name' => Auth::user()->alipay_name,
                'alipay' => Auth::user()->alipay,
                'user_id' => Auth::id(),
                'gold' => $request->drawMoney * 10,
                'money' => $request->drawMoney,
                'out_trade_no' => 'zz_' . date('YmdHis') . time() . rand(10000, 99999),
            ]);

            User::where('id', Auth::id())->update([
                'gold' => Auth::user()->gold - $request->drawMoney * 10
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '提现失败'];
        }
        return ['sta' => 1, 'msg' => '提现成功', 'info' => User::find(Auth::id())];
    }

    public function r_list (Request $request, $page, $size)
    {
        $where = Withdraws::leftJoin('users', 'users.id', 'withdraws.user_id')
            ->when($request->search['name'] != null, function ($query) use ($request) {
                return $query->where('users.name', 'like', '%' . $request->search['name'] . '%');
            })
            ->when($request->search['status'] != null, function ($query) use ($request) {
                return $query->where('withdraws.status', $request->search['status']);
            })
            ->when($request->search['user_id'] != null, function ($query) use ($request) {
                return $query->where('users.id', $request->search['user_id']);
            });
        $count = $where->count();
        $list = $where->with('user')->skip(($page - 1) * $size)->take($size)->orderBy('withdraws.created_at', 'desc')->select('withdraws.*', 'withdraws.id as wid' ,'users.id', 'users.name')->get();
        return ['sta' => 1, 'count' => $count, 'list' => $list];
    }


    public function to_draw ($id)
    {
        $draw = Withdraws::where('status','!=',9)->find($id);
        date_default_timezone_set('Asia/Shanghai');
        $aliConfig = config('aliconfig');
        $data = [
            'trans_no' => $draw->out_trade_no,
            'payee_type' => 'ALIPAY_LOGONID',
            'payee_account' => $draw->alipay,
            'amount' => '0.1',
            'remark' => '提现' . $draw->money . "元",
            'payer_show_name' => '陕西熊猫人网络科技有限公司',
            'payee_real_name' => $draw->alipay_name
        ];

        DB::beginTransaction();
        try {
            try {
                $ret = Transfer::run(Config::ALI_TRANSFER, $aliConfig, $data);
            } catch (PayException $e) {
                return ['sta' => 0, 'msg' => $e->errorMessage()];
            }
            $ret = json_decode(json_encode($ret, JSON_UNESCAPED_UNICODE),true);
            if ($ret['code'] == 10000 && $ret['msg'] == 'Success') {
                Withdraws::where('id',$id)->update([
                    'status'=>9
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['sta' => 0, 'msg' => '转账失败'];
        }
        return ['sta' => 1, 'msg' => '转账成功'];
    }

}
