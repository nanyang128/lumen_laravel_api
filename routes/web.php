<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/user/zhangsan','User\IndexController@index');

//用户登录接口
$router->post('/u/l','User\IndexController@login');

//个人中心接口
$router->get('/u/center','User\IndexController@uCenter');

//防刷接口
$router->get('order','User\IndexController@order');

$router->get('/apiFangSua','User\IndexController@apiFangSua');
$router->get('/apiFangShua',function(){
    echo __FILE__;
});