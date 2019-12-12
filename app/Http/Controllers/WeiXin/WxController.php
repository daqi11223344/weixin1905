<?php

namespace App\Http\Controllers\WeiXin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WxUserModel;

class WxController extends Controller
{
    protected $access_token;

    public function __construct(){
        $this->access_token = $this->getAccessToken();
    }

    public function getAccessToken(){
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET').'';
        $data_json = file_get_contents($url);
        $arr = json_decode($data_json,true);
        return $arr['access_token'];
    }
    
         public function wechat()
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
            die("not ok");
        }
    }


    public function receiv(){
        $log_file = "weixin1905.access.log";
        $xml = file_get_contents("php://input");
        $data = date('Y-m-d H:i:s') . $xml;
        file_put_contents($log_file,$data,FILE_APPEND);
        $xml_obj=simplexml_load_string($xml);

        $msg_type = $xml_obj->MsgType;

        $touser = $xml_obj->FromUserName;
        $fromuser = $xml_obj->ToUserName;
        $time = time();

        $event = $xml_obj->Event;  //获取事件类型
        if($event=='subscribe'){
            // 获取用户的openid
            $openid = $xml_obj->FromUserName;
            $user='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getAccessToken().'&openid='.$openid.'&lang=zh_CN';
            $user_json=file_get_contents($user);
            $user_arr=json_decode($user_json,true);
            $u = WxUserModel::where(['openid'=>$openid])->first();
            if($u){
                // TODO欢迎回来
                // echo "欢迎回来";die;
                $content = '欢迎您再次回家'.$user_arr['nickname'];
                $data=[
                    'sub_time'=>$xml_obj->CreateTime,
                    'nickname'=>$user_arr['nickname'],
                    'sex'=>$user_arr['sex'],
                ];
                WxUserModel::where('openid','=',$openid)->update($data);
                $jie='<xml>
                    <ToUserName><![CDATA['.$touser.']]></ToUserName>
                    <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                    <CreateTime>'.$time.'</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA['.$content.']]></Content>
                    </xml>';
                echo $jie;
            }else{

            
                $content='感谢您的关注'.$user_arr['nickname'];
                  //第一次关注添加入库
            $data=[
                'openid'=>$openid,
                'sub_time'=>$xml_obj->CreateTime,
                'nickname'=>$user_arr['nickname'],
                'sex'=>$user_arr['sex'],
            ];
                // openid入库
                WxUserModel::insertGetId($data);
                $jie='<xml>
                    <ToUserName><![CDATA['.$touser.']]></ToUserName>
                    <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                    <CreateTime>'.$time.'</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA['.$content.']]></Content>
                    </xml>';
             echo $jie;
            }
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN';
            $user_info = file_get_contents($url);
            file_put_contents('wx_user.log',$user_info,FILE_APPEND);
        }

        

        // 回复文本
        if($msg_type=='text'){
            $content = date('Y-m-d H:i:s') . $xml_obj->Content;
            $response_text = '<xml>
                <ToUserName><![CDATA['.$touser.']]></ToUserName>
                <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                <CreateTime>'.$time.'</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA['.$content.']]></Content>
                </xml>';
            echo $response_text;            // 回复用户消息
        }

        // 回复图片
        if($msg_type=='image'){
            $content = $xml_obj->MediaId;
            $response_text = '<xml>
                <ToUserName><![CDATA['.$touser.']]></ToUserName>
                <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                <CreateTime>'.$time.'</CreateTime>
                <MsgType><![CDATA[image]]></MsgType>
                <Image>
                    <MediaId><![CDATA['.$content.']]></MediaId>
                </Image>
                </xml>';
            echo $response_text;            // 回复用户消息
        }

        // 回复语音
        if($msg_type=='voice'){
            $content = $xml_obj->MediaId;
            $response_text = '<xml>
                <ToUserName><![CDATA['.$touser.']]></ToUserName>
                <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                <CreateTime>'.$time.'</CreateTime>
                <MsgType><![CDATA[voice]]></MsgType>
                <Voice>
                    <MediaId><![CDATA['.$content.']]></MediaId>
                </Voice>
                </xml>';
            echo $response_text;            // 回复用户消息
        }

        // 回复视频
        // if($msg_type=='video'){
        //     $content = $xml_obj->MediaId;
        //     $response_text = '<xml>
        //         <ToUserName><![CDATA['.$touser.']]></ToUserName>
        //         <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
        //         <CreateTime>'.$time.'</CreateTime>
        //         <MsgType><![CDATA[video]]></MsgType>
        //         <Video>
        //             <MediaId><![CDATA['.$content.']]></MediaId>
        //             <Title><![CDATA[title]]></Title>
        //             <Description><![CDATA[description]]></Description>
        //         </Video>
        //         </xml>';
        //     echo $response_text;            // 回复用户消息
        // }
    }


    /**
     * 获取用户基本信息
     *
     * @return void
     */
    public function UserInfo($openid,$access_token){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
    
        $json_str = file_get_contents($url);
        $log_file = 'wx_user.log';
        file_put_contents($log_file,$json_str,FILE_APPEND);
    }


}
