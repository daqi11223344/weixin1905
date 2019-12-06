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
}
