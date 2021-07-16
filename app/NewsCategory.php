<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    protected $connection = 'news';
    protected $table = 'news_category';

    protected $fillable = [
        'status','category_name','slug'
    ];

    public function news_category_detail(){
        return $this->hasMany(NewsCategoryDetail::class, 'id_category');
    }
}
