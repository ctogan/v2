<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $connection = 'common';
    protected $table = 'city';

    protected $fillable = [
        'row_status','province_id','city_name'
    ];
}
