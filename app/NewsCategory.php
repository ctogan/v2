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
}
