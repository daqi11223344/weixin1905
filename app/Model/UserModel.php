<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    public $table = "p_user";
    protected $primarykey = "user_id";
    protected $fillable = [''];
    
}
