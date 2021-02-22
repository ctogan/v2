<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCandidateReported extends Model
{
    protected $connection = 'common';
    protected $table = 'job_candidate_reported';

    protected $fillable = [
        'id','uid','reported_by','status_row'
    ];
}
