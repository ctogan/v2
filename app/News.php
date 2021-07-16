<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $connection = 'news';
    protected $table = 'news';

    protected $fillable = [
        'row_status','id_news','news_code','featured_media_id', 'source_name','category','author','title','description','url','url_to_image','content', 'view_content','published_at','created_by','created_at', 'updated_by', 'updated_at',"is_tester", "reward","is_recommendation"
    ];

    public function news_read(){
        return $this->hasOne(NewsRead::class,'id_news');
    }
}
