<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $connection = 'common';
    protected $table = 'banners';

    protected $fillable = [
        'row_status','banner_name','img', 'deeplink','sequence','created_by','created_at', 'updated_by', 'updated_at'
    ];
}
