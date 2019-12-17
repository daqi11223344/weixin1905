<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ModelMsgModel extends Model
{
    public $table = "p_wx_msg";
    protected $primarykey = "mid";
    protected $fillable = [''];
}
