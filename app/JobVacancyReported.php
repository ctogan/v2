<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobVacancyReported extends Model
{
    protected $connection = 'common';
    protected $table = 'job_vacancy_reported';

    protected $fillable = [
        'id','uid','vacancy_id','reason_id'
    ];
}
