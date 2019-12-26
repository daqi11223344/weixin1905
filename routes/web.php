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

// Route::get('/', function () {
// //    $file_name = "adc.mp3";
// //    $info = pathinfo($file_name);
// //
// //    echo $file_name . '的文件扩展名为 ：' . pathinfo($file_name)['extension'];die;
// //    echo '<pre>';print_r($info);echo '</pre>';die;
//    return view('welcome');
// });

Route::get('/',function(){
	var_dump($_GET);
});      //网站首页

Route::get('/','Index\IndexController@index');

Route::get('shop','Goods\GoodsController@shop');


Route::get('/info',function(){
	phpinfo();
});
Route::get('/take/hello','Take\TakeController@hello');

Route::get('/user/login','User\UserController@login');

Route::get('/take/redis1','Take\TakeController@redis1');
Route::get('/take/redis2','Take\TakeController@redis2');
Route::get('/take/baidu','Take\TakeController@baidu');

Route::get('/take/xml','Take\TakeController@xmlTake');
Route::get('/dev/redis/del','VoceController@delKey');

Route::get('/wx','WeiXin\WxController@wechat');
Route::post('/wx','WeiXin\WxController@receiv');        //接受微信的推送事件
Route::get('/wx/media','WeiXin\WxController@getmedia');        //获取临时素材
Route::get('/wx/test','WeiXin\WxController@test');        //获取临时素材
Route::get('/wx/msg','WeiXin\WxController@msg');        //图片
Route::get('/wx/qrcode','WeiXin\WxController@qrcode');  //创建参数的我的二维码

Route::get('/wx/sendMsg','WeiXin\WxController@sendMsg');


Route::get('/wx/menu','WeiXin\WxController@createMenu');

Route::get('/voce','VoceController@index');


Route::get('/weixin/index','WX\WeiXinController@index');
Route::get('/weixin','WX\WeiXinController@weixin');
Route::post('/weixin','WX\WeiXinController@log');

// 签到
Route::get('/sign/index','Sign\SignController@index');
Route::get('/sign','Sign\SignController@sign');
Route::post('/sign','Sign\SignController@add');






