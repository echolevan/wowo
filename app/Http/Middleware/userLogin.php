<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class userLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check()){
            $sta['sta'] = 0;
            $sta['msg'] = '请先登录';
            echo json_encode($sta);
            exit;
        }
        return $next($request);
    }

}
