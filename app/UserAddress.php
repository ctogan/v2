<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $connection = 'users';
    protected $table = 'user_address';

    protected $fillable = [
        'uid',
        'alamat_1'
    ];
}
