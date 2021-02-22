<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCandidateBookmark extends Model
{
    protected $connection = 'common';
    protected $table = 'job_bookmark_candidate';

    protected $fillable = [
        'row_status','uid','company_id','created_at','created_by','updated_at','updated_by'
    ];
}
