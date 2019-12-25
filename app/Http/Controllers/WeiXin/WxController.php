<?php

namespace App\Http\Controllers\WeiXin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WxUserModel;
use App\Model\ModelImgModel;
use App\Model\VoiceModel;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;


class WxController extends Controller
{
    protected $access_token;

    public function __construct(){
        $this->access_token = $this->getAccessToken();
    }


    /**
     * 输出access_token
     *
     * @return void
     */
    public function test(){
        echo $this->access_token;
    }

    /**
     * 获取access_token
     *
     * @return void
     */
    public function getAccessToken(){
        $Key = 'wx_access_token';
        $access_token = Redis::get($Key);
        // var_dump($access_token);die;
        if($access_token){
            return $access_token;
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET').'';
        $data_json = file_get_contents($url);
        $arr = json_decode($data_json,true);
        Redis::set($Key,$arr['access_token']);
        redis::expire($Key,3600);
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
            // die("not ok");
        }
    }


    public function receiv()
    {
        $log_file = "weixin1905.access.log";    //微信日志
        $xml_str = file_get_contents("php://input");
        $data = date('Y-m-d H:i:s') . "\n" . $xml_str;

        file_put_contents($log_file, $data, FILE_APPEND);
        $xml_obj = simplexml_load_string($xml_str);

        $msg_type = $xml_obj->MsgType;
        // dd($msg_type);
        $touser = $xml_obj->FromUserName;
        $fromuser = $xml_obj->ToUserName;
        $time = time();

        $event = $xml_obj->Event;  //获取事件类型
        if ($event == 'subscribe') {
            // 获取用户的openid
            $openid = $xml_obj->FromUserName;
            $user = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $this->getAccessToken() . '&openid=' . $openid . '&lang=zh_CN';
            $user_json = file_get_contents($user);
            $user_arr = json_decode($user_json, true);
            $u = WxUserModel::where(['openid' => $openid])->first();
            if ($u) {
                // TODO欢迎回来
                // echo "欢迎回来";die;
                $content = '欢迎您再次回家' . $user_arr['nickname'];
                $data = [
                    'sub_time' => $xml_obj->CreateTime,
                    'nickname' => $user_arr['nickname'],
                    'sex' => $user_arr['sex'],
                ];
                WxUserModel::where('openid', '=', $openid)->update($data);
                $jie = '<xml>
                    <ToUserName><![CDATA[' . $touser . ']]></ToUserName>
                    <FromUserName><![CDATA[' . $fromuser . ']]></FromUserName>
                    <CreateTime>' . $time . '</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[' . $content . ']]></Content>
                    </xml>';
                echo $jie;
            } else {


                $content = '感谢您的关注' . $user_arr['nickname'];
                //第一次关注添加入库
                $data = [
                    'openid' => $openid,
                    'sub_time' => $xml_obj->CreateTime,
                    'nickname' => $user_arr['nickname'],
                    'sex' => $user_arr['sex'],
                    'headimgurl'=>$user_arr['headimgurl'],
                ];
                // openid入库
                WxUserModel::insertGetId($data);
                $jie = '<xml>
                    <ToUserName><![CDATA[' . $touser . ']]></ToUserName>
                    <FromUserName><![CDATA[' . $fromuser . ']]></FromUserName>
                    <CreateTime>' . $time . '</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[' . $content . ']]></Content>
                    </xml>';
                echo $jie;
            }
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $this->access_token . '&openid=' . $openid . '&lang=zh_CN';
            $user_info = file_get_contents($url);
            file_put_contents('wx_user.log', $user_info, FILE_APPEND);
        }elseif($event=='CLICK'){
            if($xml_obj->EventKey=='weather'){

                //如果是获取天气

                //请求第三方接口 获取天气
                $weather_api='https://free-api.heweather.net/s6/weather/now?location=beijing&key=090802638cdf46d4bd6162c2940cc871';
                $weather_info = file_get_contents($weather_api);
                $weather_info_arr = json_decode($weather_info,true);
//                echo '<pre>';
//                print_r($weather_info_arr);
//                echo '</pre>';
//                die;

                $cond_txt = $weather_info_arr['HeWeather6'][0]['now']['cond_txt'];
                $tmp = $weather_info_arr['HeWeather6'][0]['now']['tmp'];
                $wind_dir = $weather_info_arr['HeWeather6'][0]['now']['wind_dir'];

                $msg = $cond_txt ."\n" . '温度: '.$tmp. "\n" . '风向: '. $wind_dir;


                $response_xml='
                <xml>
                    <ToUserName><![CDATA[' . $touser . ']]></ToUserName>
                    <FromUserName><![CDATA[' . $fromuser . ']]></FromUserName>
                    <CreateTime>' . $time . '</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[' . date('Y-m-d H:i:s') .  $msg . ']]></Content>
                </xml>';
                echo $response_xml;
            }

        }


        $media_id = $xml_obj->MediaId;
        $touser = $xml_obj->FromUserName;
        $uid = WxUserModel::where('openid', '=', $touser)->value('uid');
//        dd($uid);
        // 回复文本
        if ($msg_type == 'text') {
            $content = date('Y-m-d H:i:s') . $xml_obj->Content;
            $response_text = '<xml>
                <ToUserName><![CDATA[' . $touser . ']]></ToUserName>
                <FromUserName><![CDATA[' . $fromuser . ']]></FromUserName>
                <CreateTime>' . $time . '</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[' . $content . ']]></Content>
                </xml>';
            echo $response_text;            // 回复用户消息


        } elseif ($msg_type == 'image') {     // 回复图片
            //下载图片
            $imgs = $this->getMedia2($media_id, $msg_type);
            //回复图片
            $response_text = '<xml>
                 <ToUserName><![CDATA[' . $touser . ']]></ToUserName>
                 <FromUserName><![CDATA[' . $fromuser . ']]></FromUserName>
                 <CreateTime>' . $time . '</CreateTime>
                 <MsgType><![CDATA[image]]></MsgType>
                 <Image>
                     <MediaId><![CDATA[' . $media_id . ']]></MediaId>
                 </Image>
                 </xml>';
            echo $response_text;
                if ($response_text) {
                    $data = [
                        'uid' => $uid,
                        'img_time' => time(),
                        'imgs' => $imgs,
                    ];

                    $post = ModelImgModel::insertGetId($data);

                }
            } elseif ($msg_type == 'voice') {     // 回复语音
                //下载语音
                $voice = $this->getMedia2($media_id, $msg_type);
                //回复语音
                $response_text = '<xml>
                 <ToUserName><![CDATA[' . $touser . ']]></ToUserName>
                 <FromUserName><![CDATA[' . $fromuser . ']]></FromUserName>
                 <CreateTime>' . $time . '</CreateTime>
                 <MsgType><![CDATA[voice]]></MsgType>
                 <Voice>
                     <MediaId><![CDATA[' . $media_id . ']]></MediaId>
                 </Voice>
                 </xml>';
                echo $response_text;
                    if ($response_text) {
                        $data = [
                            'uid' => $uid,
                            'voice_time' => time(),
                            'voice' => $voice,
                        ];

                        $pos = VoiceModel::insertGetId($data);

                    }

            }
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

    
    /**
     * 自定义菜单
     *
     * @return void
     */
        public function createMenu(){

            $url = 'http://wangqi.bianaoao.top/voce';
            $url2 = 'http://wangqi.bianaoao.top/';
            $redirect_uri = urlencode($url);
            $redirect_uri2 = urlencode($url2);//授权后跳转页面

            // 创建自定义菜单的接口地址
           $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->access_token;

                   $menu = [
                       'button' => [
                           [
                               'type' => 'click',
                               'name' => '获取天气',
                               'key' =>'weather',
                           ],
                           [
                               'name'=>'点我❤',
                               'sub_button' => [
                                   ['type' => 'view',
                                       'name' => '商店',
                                       'url' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx92b4938777947dcd&redirect_uri='.$redirect_uri2.'&response_type=code&scope=snsapi_userinfo&state=ABCD1905#wechat_redirect',
                                   ],
                                   ['type' => 'view',
                                       'name' => '投票',
                                       'url' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx92b4938777947dcd&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=ABCD1905#wechat_redirect',
                                   ],
                                   ['type' => 'view',
                                        'name' => '看看↓',
                                        'url' => 'http://kphbeijing.m.chenzhongtech.com/s/T8wVF0mu',
                                   ],
                               ],
                             ]
                           ]

           ];

            $menu_json = json_encode($menu,JSON_UNESCAPED_UNICODE);
            $client = new Client();
            $response = $client->request('POST',$url,[
                'body' => $menu_json
            ]);
            echo '<pre>';
            print_r($menu);
            echo '</pre>';
            echo $response->getBody();  //接受 微信接口的响应数据

        }
    /**
     * 消息群发
     *
     * @return void
     */
    public function sendMsg(){
        echo __METHOD__;


        $weather_api='https://free-api.heweather.net/s6/weather/now?location=beijing&key=090802638cdf46d4bd6162c2940cc871';
        $weather_info = file_get_contents($weather_api);
        $weather_info_arr = json_decode($weather_info,true);
            //    echo '<pre>';
            //    print_r($weather_info_arr);
            //    echo '</pre>';
            //    die;

        $cond_txt = $weather_info_arr['HeWeather6'][0]['now']['cond_txt'];
        $tmp = $weather_info_arr['HeWeather6'][0]['now']['tmp'];
        $wind_dir = $weather_info_arr['HeWeather6'][0]['now']['wind_dir'];

        $m = $cond_txt ."\n" . '温度: '.$tmp. "\n" . '风向: '. $wind_dir;

        $openid = WxUserModel::select('openid','nickname','sex')->get()->toArray();
        // echo '<pre>';print_r($openid);echo '</pre>';

        $open = array_column($openid,'openid');
        echo '<pre>';print_r($open);echo '</pre>';

        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=28_7rqVghaNI-Q8iecPFHIYew-lIspeRDbPye4tDRHCrjuwXTaFdt2DmiErR9Yvp-BW5F-NPvQFIYJFWsyHw7hx0bSMt4nvARbiHmBVZDaWwh1a_BtrzdC0rhyttXGiAEk8gDM08ZLsP9itqE1yRSMbAFABCW';
        $msg = date('Y-m-d H:i:s') ."\n" . $m . "\n"  . '那个 This is my make 的微信测试号，you look look and try 一下 ';

        $data = [
            'touser'    =>$open,
            'msgtype'   =>'text',
            'text'      =>['content'=>$msg]
        ];

        $client = new Client();
        $response = $client->request('POST',$url,[
            'body'  => json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);

        echo $response->getBody();echo "\n";


    }

    /**
     * 生成二维码
     *
     * @return void
     */
    public function qrcode(){

        $scene_id = $_GET['scene'];     //二维码参数

        //$access_token = WxUserModel::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->access_token;

        $post = [
            'expire_seconds'    =>604800,
            'action_name'       =>'QR_SCENE',
            'action_info'       =>[
                'scene' =>[
                    'scene_id' => $scene_id
                ]
            ]
        ];
        $client = new Client();
        $response = $client->request('POST',$url,[
            'body' => json_encode($post)
        ]);

        $json1 = $response->getBody();

        $toket = json_decode($json1,true)['ticket'];

        //第二部获取带参数的二维码

        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$toket;

        return redirect($url);
    }

    



}





