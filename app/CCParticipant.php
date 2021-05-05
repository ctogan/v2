<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CCParticipant extends Model
{
    protected $connection = 'game_center';
    protected $table = 'cc_participant';
    public $timestamps = false;

    protected $fillable = [
        'row_status','cc_session_id','uid','last_point','cc_register_date','app_register_date','time_start','time_end','score'
    ];

    public function session(){
        return $this->belongsTo(CCSession::class, 'cc_session_id');
    }
}