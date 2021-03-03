<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplicant extends Model
{
    protected $connection = 'common';
    protected $table = 'job_applicant';

    protected $fillable = [
        'id','uid','name','vacancy_id','apply_date'
    ];
}
