<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTargetInfo extends Model
{
    protected $connection = 'users';
    protected $table = 'user_target_info';

    protected $primaryKey = 'uid';
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'uid',
        'tm_target_changed',
        'locale',
        'opcode',
        'osver',
        'appver',
        'resw',
        'resh',
        'lat',
        'lng',
        'gender',
        'birth',
        'marriage',
        'religion',
        'device_name',
    ];
}
