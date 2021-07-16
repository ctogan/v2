<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PointPurchase extends Model
{
    protected $connection = 'common';
    protected $table = 'point_purchase';

    public $timestamps = false;

    protected $fillable = [
        'uid','transaction_code','description','price','tm'
    ];
}
