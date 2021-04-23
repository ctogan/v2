<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsCategoryDetail extends Model
{
    protected $connection = 'news';
    protected $table = 'news_category_detail';

    protected $fillable = [
        'id_news','id_category'
    ];
}
