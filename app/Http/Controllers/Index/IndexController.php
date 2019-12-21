<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WxUserModel;

class IndexController extends Controller
{

    public function index()
    {
        $code = $_GET['code'];
        $data = $this->getAccessToken($code);

        //  判断用户是否已存在
        $openid = $data['openid'];
        $u = WxUserModel::where(['openid'=>$openid])->first();
        session(['headimgurl'=>$u['headimgurl']]);
        if($u){     //用户已存在
            $user_info = $u->toArray();

        }else{
            $user_info = $this->getUserInfo($data['access_token'],$data['openid']);
            //入库
            WxUserModel::insertGetId($user_info);
        }

        $data = [
            'u' => $user_info
        ];


        return view('index.index',$data);

    }

    protected function getAccessToken($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET').'&code='.$code.'&grant_type=authorization_code';
        $json_data = file_get_contents($url);
        return json_decode($json_data,true);
    }


}
