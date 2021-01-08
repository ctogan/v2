<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $connection = 'common';
    protected $table = 'province';

    protected $fillable = [
        'row_status','province_name'
    ];
}
