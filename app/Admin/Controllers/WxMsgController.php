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

        $openid = WxUserModel::select('openid','nickname','sex')->get()->toArray();
//        echo '<pre>';print_r($openid);echo '</pre>';

        $open = array_column($openid,'openid');
        echo '<pre>';print_r($open);echo '</pre>';

        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=28_ekgqIwW5K0seSKb2Udr8Mh_o-EVm91oi3MeDg5CIh9uZ_yolwiVpfD7SSF2FU-W0v4Cdanpaw7tk5ddsuAiYBEAU91RofVGzHTHsbkT3at5nd6guaJx24Ut3h3-Zyyrg4GWGjQsxcqrC3lFKNKJdAAAWTS';
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

        echo $response->getBody();


    }



}
