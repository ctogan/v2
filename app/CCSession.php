<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CCSession extends Model
{
    protected $connection = 'game_center';
    protected $table = 'cc_session';

    protected $fillable = [
        'row_status','session_code','title','registration_fee','open_date','time_start','time_end','tnc','is_tester','random_question','displayed_question','created_by','created_at','updated_by','updated_at'
    ];

    public function question(){
        return $this->hasMany(CCSessionQuestion::class, 'cc_session_id');
    }

    public function prize(){
        return $this->hasMany(CCSessionPrize::class, 'cc_session_id')->with('product')->where('row_status','=','active')->orderBy('id','ASC');
    }

    public function participant(){
        return $this->hasMany(CCParticipant::class, 'cc_session_id');
    }
}
