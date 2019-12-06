<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;


class UserController extends Controller
{
    public function login()
    {
        $pass = '123456asd';
        $password = password_hash($pass,PASSWORD_BCRYPT);
        $email = 'zhnagsan@qq.com';

        $data = [
            'user_name' => 'zhangsan',
            'password' => $password,
            'email' => $email,
        ];
		//dd($data);

        $post = UserModel::insertGetId($data);
        dd($post);

    }

	
}
