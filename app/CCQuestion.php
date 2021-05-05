<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CCQuestion extends Model
{
    protected $connection = 'game_center';
    protected $table = 'cc_question';

    protected $fillable = [
        'row_status','question_image','question','question_level','created_by','created_at','updated_by','updated_at'
    ];

    public function answer(){
        return $this->hasMany(CCAnswer::class,'cc_question_id')->where('row_status','=', 'active');
    }

    public function correct_answer(){
        return $this->hasOne(CCAnswer::class,'cc_question_id')
            ->where('row_status','=', 'active')
            ->where('is_correct_answer','=', true);
    }

    public function session_question(){
        return $this->hasOne(CCSessionQuestion::class, 'cc_question_id');
    }
}