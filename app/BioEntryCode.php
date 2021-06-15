<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BioEntryCode extends Model
{
    protected $connection = 'users';
    protected $table = 'bio_entry_code';

    protected $fillable = [
        'id','code','code_name'
    ];

    public function bio_entry_code(){
        return $this->belongsTo(PersonalInformation::class,'code','code');
    }
}
