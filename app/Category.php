<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'common';
    protected $table = 'categories';

    protected $fillable = [
        'row_status','category_name','img', 'deeplink','sequence','created_by','created_at', 'updated_by', 'updated_at'
    ];
}
