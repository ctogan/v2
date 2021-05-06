<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CCSessionPrize extends Model
{
    protected $connection = 'game_center';
    protected $table = 'cc_session_prize';
    public $timestamps = false;

    protected $fillable = [
        'row_status','cc_session_id','cc_question_id','rank','product_id','created_by','created_at','updated_by','updated_at'
    ];

    public function session(){
        return $this->belongsTo(CCSession::class, 'cc_session_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
}