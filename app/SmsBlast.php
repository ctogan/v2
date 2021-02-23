<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsBlast extends Model
{
    protected $connection = 'common';
    protected $table = 'sms_blast';

    protected $fillable = [
        'id','uid','message','status','created_at','created_by','point_ballance','phone_number'
    ];
}
