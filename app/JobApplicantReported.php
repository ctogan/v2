<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplicantReported extends Model
{
    protected $connection = 'common';
    protected $table = 'job_applicant_reported';

    protected $fillable = [
        'id','applicant_id','vacancy_id','reason_id'
    ];
}
