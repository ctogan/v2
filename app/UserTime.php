<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTime extends Model
{
    protected $connection = 'users';
    protected $table = 'user_time';

    protected $primaryKey = 'uid';
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'uid','register','changed','login', 'last_ad_list','last_ip','ses','appopen'
    ];
}
