<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsCategoryDetail extends Model
{
    protected $connection = 'news';
    protected $table = 'news_category_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_news','id_category'
    ];

    public function category(){
        return $this->belongsTo(NewsCategory::class,'id_category');
    }
}
