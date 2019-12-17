<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VoiceModel extends Model
{
    public $table = "p_wx_voice";
    protected $primarykey = "vid";
    protected $fillable = [''];
}
