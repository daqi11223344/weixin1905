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
        // dd($data);
        
        $xml_obj = simplexml_load_string($xml);
        $msg_type = $xml_obj->MsgType;
        $touser = $xml_obj->FromUserName;
        $fromuser = $xml_obj->ToUserName;
        $time = time(); 
        $post = $xml_obj->Event;

        if($post == 'subscribe'){
            $openid = $xml_obj->FromUserName;

            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
            // dd($url);
            $json = file_get_contents($url);
            $arr = json_decode($json,true);
            // dd($arr);
            $pos = WxUserModel::where(['openid'=>$openid])->first();
            // dd($pos);

            if($pos){
                $name = '欢迎'.$arr['nickname'].'同学回来';
                $data = [
                    'nickname'   => $arr['nickname'],
                    'sub_time'   => $xml_obj->CreateTime,
                    'sex'        => $arr['sex'],

                ];
                // dd($data);
                WxUserModel::where('openid','=',$openid)->update($data);
                // dd($posk);
                $add = '<xml><ToUserName><![CDATA['.$touser.']]></ToUserName>
                            <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                            <CreateTime>'.$time.'</CreateTime>
                            <MsgType><![CDATA[event]]></MsgType>
                            <Event><![CDATA[subscribe]]></Event>
                            <EventKey><![CDATA['.$name.']]></EventKey>
                        </xml>';
                echo $add;
            }else{
                $name = '欢迎'.$arr['nickname'].'同学，感谢您的关注';
                // dd($name);
                $data = [
                    'nickname'   => $arr['nickname'],
                    'sub_time'   => $xml_obj->CreateTime,
                    'sex'        => $arr['sex'],
                    'headimgurl' => $arr['headimgurl'],
                    'openid'     => $openid,
                ];
                // dd($data);
                $poss = WxUserModel::insert($data);
                // dd($poss);
                $add = '<xml><ToUserName><![CDATA['.$touser.']]></ToUserName>
                            <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                            <CreateTime>'.$time.'</CreateTime>
                            <MsgType><![CDATA[event]]></MsgType>
                            <Event><![CDATA[subscribe]]></Event>
                            <EventKey><![CDATA['.$name.']]></EventKey>
                        </xml>';
                echo $add;
            }



        }
    }

}
