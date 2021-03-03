<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplicant extends Model
{
    protected $connection = 'common';
    protected $table = 'job_applicant';

    protected $fillable = [
        'id',
        'uid',
        'img',
        'applicant_name',
        'vacancy_id',
        'apply_date',
        'dob',
        'sex',
        'pob',
        'phone',
        'email',
        'weight',
        'height',
        'religion',
        'last_education',
        'skills',
        'hobby',
        'address',
    ];
}
