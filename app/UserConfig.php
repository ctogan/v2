<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserConfig extends Model
{
    protected $connection = 'users';
    protected $table = 'user_config';

    public $timestamps = false;

    protected $fillable = [
        'uid','auto_buying','lock_screen','allow_noti','status','abuse','sel_goods_id','is_rooted'
    ];
}
