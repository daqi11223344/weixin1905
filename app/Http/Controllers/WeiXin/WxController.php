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
                $content = '欢迎'.$user_arr['nickname'].'同学回来';
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


                $content = '欢迎'.$user_arr['nickname'].'同学，感谢您的关注';
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
                            'name' => '点我查询',
                            'key' =>'weather',
                        ],
                           [
                            'type' => 'view',
                            'name' => '点我积分❤',
                            'url' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx92b4938777947dcd&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=ABCD1905#wechat_redirect',
                           ],
                          
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

        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=28_FBKrsEB5nLpGuyYdD4d1S-PF9xOSFsEIl47Lgg6s-ZTXCJv3uwDIg2MNQO7V3W3YTi7YNsQpjIUOhudCwQebfWrp0l4mW5wI5CArhoufVVW_76IdipPZQlJHuRej-OxtrWxIV24hD_voA-MrXAMdAIAEHT';
        $msg = date('Y-m-d H:i:s') . "\n" . '尊敬的用户您好，目前公司开展签到送积分兑换活动，详情请进入公众号查看 ';

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










