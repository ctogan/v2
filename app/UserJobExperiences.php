<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserJobExperiences extends Model
{
    protected $connection = 'users';
    protected $table = 'user_job_part_time_experience';

    protected $fillable = [
        'uid',
        'company_name',
        'department',
        'position_name',
        'period',
        'description'
    ];
}
