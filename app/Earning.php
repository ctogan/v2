<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    protected $table = 'user_earning';

    protected $primaryKey = 'seq';
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'seq','uid','tm', 'code','cash','before_free_cash','before_work_cash','after_free_cash','after_work_cash','detail','uniq'
    ];
}