<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class ApiCheckToken
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

        $ip=$request->ip();//接收访问的ip
        $num=Redis::incr($ip);   // key 中储存的数字值增一
        Redis::expire($ip,60);  //60秒   每分钟请求

        if($num>20){
            //拒绝服务1分钟
            $response=[
                'error'=>0,
                'msg'=>'Invalid Request',
            ];
            Redis::expire($ip,60);  //拒绝访问1分钟
            echo json_encode($response);die;

        }

        return $next($request);
    }
}
