<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoceController extends Controller
{
    public function index(){
        echo '<pre>';
        print_r($_GET);
        echo '</pre>';

        $code = $_GET['code'];

        //获取access_token
        $this->getAccessToken($code);

    }

    /*
     *根据code获取access_token
     *
     */

    protected function getAccessToken(){

        $url= 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APPID').'&sercet='.env('WX_APPSECRET').'&code='.code.'&grant_type=authorization_code';

        $json_data = file_get_contents($url);
        $data = json_decode($json_data,true);
        echo '<pre>';
        print_r($data);
        echo '</pre>';

    }

}













