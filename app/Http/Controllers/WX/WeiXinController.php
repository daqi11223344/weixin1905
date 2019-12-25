<?php

namespace App\Http\Controllers\WX;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WxUserModel;

class WeiXinController extends Controller
{
    public function index()
    {
        echo $token = WxUserModel::getAccessToken();
    }

    public function weixin()
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

    // 将微信服务器的推送数据保存到日志文件；
    public function log()
    {
        // 日志文件
        $log = 'wq.log';
        $token = WxUserModel::getAccessToken();
        $xml = file_get_contents('php://input');

        $data = date('Y-m-d H:i:s'). "\n" .$xml;
        file_put_contents($log,$data,FILE_APPEND);

        $xml_obj = simplexml_load_string($xml);
        $msg_type = $xml_obj->MsgType;
        $touser = $xml_obj->FromUserName;
        $fromuser = $xml_obj->ToUserName;
        $time = time();
        // dd($xml_obj);
        $pos = $xml_obj->Event;
        if($pos == 'subscribe'){
            // 获取用户OpenId
            $openid = $xml_obj->FromUserName;
            $user = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
            $json = file_get_contents($user);
            $arr = json_decode($json,true);
            $array = WxUserModel::where(['openid'=>$openid])->first();
            // dd($array);
            // 判断是否为新用户
            if($array){
                $content = '浪够了回来了' . $array['nickname'];
                // dd($content);
                $data = [
                    'sub_time' => $xml_obj->CreateTime,
                    'nickname' => $array['nickname'],
                    'sex'      => $array['sex'],   
                ];
                // dd($data);
                $post  =WxUserModel::where('openid','=',$openid)->update($data);
                // dd($post);
                $wx = '<xml><ToUserName><![CDATA['.$touser.']]></ToUserName>
                    <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                    <CreateTime>'.$time.'</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[subscribe]]></Event>
                    <EventKey><![CDATA['.$content.']]></EventKey>
                    </xml>';
                echo $wx;
            }else{
                $content = '您好，欢迎来到我们的大家庭' . $array['nickname'];
                // 第一次关注添加入库
                $data = [
                    'openid'   => $openid,
                    'sub_time' => $xml_obj->CreateTime,
                    'nickname' => $array['nickname'],
                    'sex'      => $array['sex'],
                    'headimgurl' => $array['headimgurl'],
                ];
                // dd($data);
                WxUserModel::insertGetId($data);
                $wx = '<xml><ToUserName><![CDATA['.$touser.']]></ToUserName>
                    <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                    <CreateTime>'.$time.'</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[subscribe]]></Event>
                    <EventKey><![CDATA['.$content.']]></EventKey>
                    </xml>';
                echo $wx;
            }
        }


    }
}
