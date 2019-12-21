<?php

namespace App\Admin\Controllers;

use App\Model\WxUserModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use GuzzleHttp\Client;

class WxMsgController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '微信用户管理';

    public function sendMsg(){
        echo __METHOD__;


        $weather_api='https://free-api.heweather.net/s6/weather/now?location=beijing&key=090802638cdf46d4bd6162c2940cc871';
        $weather_info = file_get_contents($weather_api);

        $openid = WxUserModel::select('openid','nickname','sex')->get()->toArray();
//        echo '<pre>';print_r($openid);echo '</pre>';

        $open = array_column($openid,'openid');
        echo '<pre>';print_r($open);echo '</pre>';

        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=28_vC6K8sjCp4hB75B65eohuAVXll7IaSmgEgdpYgorXSHWuJRgEqrUrZ2K08Hco7-qTGAv9beCgdeaN5vsXuwxgU63q1_sNrR0qQmA3SP7W5KTYtSqsBtZPs_Rpf3yvh-wsevophoixdXU2Ue-ZMXeAEAUHS';
        $msg = date('Y-m-d H:i:s') ."\n" . '那个 This is my nake 的微信测试号，you look look and try 一下 ';

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



}
