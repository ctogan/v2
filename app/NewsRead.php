<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsRead extends Model
{
    protected $connection = 'news';
    protected $table = 'news_read';

    protected $fillable = [
        'id_news','uid','reward','created_at','updated_at'
    ];

    public function news(){
        return $this->hasMany(News::class, 'id_news');
    }
}
