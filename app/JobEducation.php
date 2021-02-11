<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobEducation extends Model
{
    protected $connection = 'common';
    protected $table = 'job_education';

    protected $fillable = [
        'id','education','created_at'
    ];

}
