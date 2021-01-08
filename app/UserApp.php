<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserApp extends Model
{
    protected $connection = 'users';
    protected $table = 'users';

    protected $fillable = [
        'uid','sim','anid','imei', 'gaid','phone','phone_auth_tm','mtime','inv_code','user_type','country_code'
    ];
}
