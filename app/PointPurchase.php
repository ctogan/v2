<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PointPurchase extends Model
{
    protected $connection = 'common';
    protected $table = 'point_purchase';

    protected $fillable = [
        'id','uid','transaction_code','description','price','tm'
    ];
}
