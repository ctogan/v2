<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CCParticipantDetail extends Model
{
    protected $connection = 'game_center';
    protected $table = 'cc_participant_detail';
    public $timestamps = false;

    protected $fillable = [
        'cc_participant_id','question_id','answer_id','score', 'answer_date'
    ];
}