<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsBlast extends Model
{
    protected $connection = 'common';
    protected $table = 'list_sms';

    protected $fillable = [
        'id','uid','message','status','created_at','updated_at','point_balance','phone_number'
    ];
}
