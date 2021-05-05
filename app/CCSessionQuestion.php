<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CCSessionQuestion extends Model
{
    protected $connection = 'game_center';
    protected $table = 'cc_session_question';
    public $timestamps = false;

    protected $fillable = [
        'cc_session_id','cc_question_id'
    ];

    public function session(){
        return $this->belongsTo(CCSession::class, 'cc_session_id');
    }

    public function question(){
        return $this->belongsTo(CCQuestion::class, 'cc_question_id');
    }
}
