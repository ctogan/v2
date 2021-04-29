<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'common';
    protected $table = 'categories';

    protected $appends = array('category_name');

    protected $fillable = [
        'row_status','category_name_eng','category_name_id','img', 'deeplink','sequence','created_by','created_at', 'updated_by', 'updated_at'
    ];

    public function getCategoryNameAttribute()
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            return $this->attributes['category_name_id'];
        }else{
            return $this->attributes['category_name_eng'];
        }
    }
}
