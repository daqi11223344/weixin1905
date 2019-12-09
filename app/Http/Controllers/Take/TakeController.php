<?php

namespace App\Http\Controllers\Take;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

use GuzzleHttp\Client;

class TakeController extends Controller
{
	public function hello(){
		echo "hello world 1212";
	}

	public function redis1(){
		$key = 'weixin';
		$val = 'hello world';
		Redis::set($key,$val);

		echo time(); echo"<br>";
		echo date('Y-m-d H:i:s');
	}

	public function redis2(){
		$key = 'weixin';
		echo Redis::get($key);
	}

    //请求百度
    public function baidu(){
        $url = 'http://m.news.cctv.com/2019/12/04/ARTIx273eYyf2iAfANxFBUPm191204.shtml';
        $client = new Client();
        $response = $client->request('GET',$url);
        echo $response->getBody();
	}
	
	public function xmlTake(){
		$xml_str = '<xml>
		<ToUserName><![CDATA[gh_e0e1400f9028]]></ToUserName>
		<FromUserName><![CDATA[oksNvw1XZJXTJNvebWzKI65aw4Mg]]></FromUserName>
		<CreateTime>1575875524</CreateTime>
		<MsgType><![CDATA[text]]></MsgType>
		<Content><![CDATA[发个广告]]></Content>
		<MsgId>22561114852747715</MsgId>
		</xml>';

		$xml_obj = (array)simplexml_load_string($xml_str);
		

		print_r($xml_obj);die;
		print_r($xml_obj);echo '<hr>';
		echo '<hr>';
		echo 'ToUserName: '.$xml_obj->ToUserName;echo '<br>';
		echo 'FromUserName: '.$xml_obj->FromUserName; 
	}
}
