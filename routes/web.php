<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/info',function(){
	phpinfo();
});
Route::get('/take/hello','Take\TakeController@hello');

Route::get('/user/login','User\UserController@login');

Route::get('/take/redis1','Take\TakeController@redis1');
Route::get('/take/redis2','Take\TakeController@redis2');
Route::get('/take/baidu','Take\TakeController@baidu');

Route::get('/wx','WeiXin\WxController@wechet');




