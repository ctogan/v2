<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobUserPreferenceEducation extends Model
{
    protected $connection = 'common';
    protected $table = 'job_user_preference_education';
    protected $fillable = [
        'id','uid','id_education','created_at'
    ];
}
