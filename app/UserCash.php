<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCash extends Model
{
    protected $connection = 'users';
    protected $table = 'user_cash3';

    protected $primaryKey = 'uid';
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'uid','total_earn','total_use','last_earn','month_earn','total_pulsa','parent_uid','inv_count','inv_cash_total'
    ];
}
