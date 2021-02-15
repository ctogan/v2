<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobUserPreferenceProvince extends Model
{
    protected $connection = 'common';
    protected $table = 'job_user_preference_province';
    protected $fillable = [
        'id','id_province','created_at','uid'

    ];
}
