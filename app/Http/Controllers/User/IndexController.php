<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class IndexController
{
    //测试
    public function index(){
        $arr=[
            'name'=>'nanyang',
            'age'=>18,
            'pwd'=>'ny128123',
        ];
        return $arr;
    }


    public $redis_h_u_key='api:h:u';  //用户个人信息hash

    /**
     * 用户登录接口
     * @param Request $request
     */
    public function login(Request $request){
        $username=$request->input('u');//用户名
        $password=$request->input('p');//密码

       //echo '<pre>';print_r($request->input());echo '</pre>';

        //验证用户信息
        if(1){
            //登录成功，生成一个加密的token,用于身份验证
            $uid=2;
            $str=time()+$uid+mt_rand(10000,99999);
            $token=substr(md5($str),5,15);

            //将token保存到redis
            $key=$this->redis_h_u_key.$uid;
            Redis::hSet($key,'token',$token);
            Redis::expire($key,3600*24*7);//设置token的过期时间
            echo $token;
        }else{
            //失败
            echo '登录失败';
        }
    }

    /**
     * 个人中心接口
     * @return array
     */
    public function uCenter(){

        //客户端通过header将数据传过来，这里接一下
        //echo '<pre>';print_r(print_r($_SERVER));echo '</pre>';
        $uid=$_GET['uid']; //用户ID
        if(empty($_SERVER['HTTP_TOKEN'])){
            $response=[
                'status'=>0,
                'msg'=>'token mistake',
            ];
        }else{

            //验证token是否有效，是否伪造，是否过期
            $key=$this->redis_h_u_key.$uid;
            $token=Redis::hGet($key,'token');

            if($token==$_SERVER['HTTP_TOKEN']){
                $response=[
                    'status'=>200,
                    'msg'=>'token ok',
                    'data'=>[
                        'user'=>'lisi',
                        'age'=>22,
                    ]
                ];
            }else{
                $response=[
                    'error'=>5000,
                    'msg'=>"Invalid Token"
                ];
            }
        }

        return $response;
    }

    //防刷
    public function order(){
        //echo '<pre>';print_r(print_r($_SERVER));echo '</pre>';

        $request_uri=$_SERVER['REQUEST_URI']; //获取访问路径
        $uri_hash=substr(md5($request_uri),5,15);  //对访问路径加密

        $ip=$_SERVER['REMOTE_ADDR']; //客户端访问地址

        $redis_key='str:'.$uri_hash.':'.$ip;  //拼接成键名

        $num=Redis::incr($redis_key);   // key 中储存的数字值增一
        Redis::expire($redis_key,60);  //60秒   每分钟请求

        //判断访问次数是否访问超了10次，如果超了防止请求
        if($num>10){
            //拒绝服务10分钟
            $response=[
                'error'=>0,
                'msg'=>'Invalid Request',
            ];
            Redis::expire($redis_key,60);  //拒绝访问1分钟

        }else{
            $response=[
                'error'=>200,
                'msg'=>'ok',
                'data'=>[
                    'aa'=>'dd'
                ]
            ];
        }

        return $response;
    }

    public function apiFangSua(){
        echo 123;
    }

}
