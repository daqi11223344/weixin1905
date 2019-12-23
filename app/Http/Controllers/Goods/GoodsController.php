<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\GoodsModel;

class GoodsController extends Controller
{
    public function shop(Request $request)
    {
//dd(1234);
        $goods_id = $request->input('id');
//        dd($goods_id);
        $goods = GoodsModel::find($goods_id);
//         echo '<pre>';print_r($goods->toArray());echo '</pre>';die;
        return view('index/shop',['goods'=>$goods]);
    }
}
