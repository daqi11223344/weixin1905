<?php

namespace App\Http\Controllers\Sign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WxUserModel;

class SignController extends Controller
{
    public function index()
    {
        echo $token = WxUserModel::getAccessToken();
    }

    public function sign()
    {
        $token = '2259b56f5898cd6192c50';       //开发提前设置好的 token
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET["echostr"];


        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );


        if( $tmpStr == $signature ){        //验证通过
            echo $echostr;
        }else{
            // die("not ok");
        }
    }

    public function add()
    {
        $log = 'add.log';
        $token = WxUserModel::getAccessToken();
        $xml = file_get_contents('php://input');

        $data = date('Y-m-d H:i:s') . ">>>>>\n" . $xml;
        file_put_contents($log,$data,FILE_APPEND);
        dd($data);
    }

}
