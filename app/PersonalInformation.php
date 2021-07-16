<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    protected $connection = 'users';
    protected $table = 'user_bio_entry';
    public $timestamps = false;

    protected $fillable = [
       'uid','code','value','register','got_rwd'
    ];

    public function entry_code(){
//        return $this->hasOne('App\BioEntryCode','code','code');
        return $this->hasOne(BioEntryCode::class,'code', 'code');
    }

    public function entry_value(){
        return $this->hasOne('App\BioEntryValue','value','value');
    }
}
