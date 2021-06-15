<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CCAnswer extends Model
{
    protected $connection = 'game_center';
    protected $table = 'cc_answer';

    public $timestamps = false;

    protected $fillable = [
        'row_status','cc_question_id','answer','is_correct_answer'
    ];

    public function question(){
            return $this->belongsTo(CCQuestion::class,'cc_question_id');
    }
}