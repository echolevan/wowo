<?php

namespace App\Http\Controllers;

use App\Recharge;
use Illuminate\Http\Request;

class RechargeController extends Controller
{
    //

    public function r_list (Request $request, $page, $size)
    {
        $where = Recharge::leftJoin('users', 'users.id', 'recharges.user_id')
            ->when($request->search['name'] != null, function ($query) use ($request) {
                return $query->where('users.name', 'like', '%' . $request->search['name'] . '%');
            })
            ->when($request->search['status'] != null, function ($query) use ($request) {
                return $query->where('recharges.status', $request->search['status']);
            })
            ->when($request->search['user_id'] != null, function ($query) use ($request) {
                return $query->where('users.id', $request->search['user_id']);
            });
        $count = $where->count();
        $list = $where->with('user')->skip(($page - 1) * $size)->take($size)->orderBy('recharges.created_at', 'desc')->select('recharges.*' , 'users.id', 'users.name')->get();
        return ['sta' => 1, 'count' => $count, 'list' => $list];
    }
}
