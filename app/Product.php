<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'common';
    protected $table = 'products';

    protected $fillable = [
        'row_status','product_code','product_name','img','created_at','created_by','updated_at','updated_by'
    ];
}