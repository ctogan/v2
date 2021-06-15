<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $connection = 'earn_2';

    protected $table = 'user_earning_data';

    protected $fillable = [
        'seq','uid','body','tm' ,'code' ,'cash','before_cash','after_cash','detail','uniq'
    ];
}