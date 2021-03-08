<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobFAQ extends Model
{
    protected $connection = 'common';
    protected $table = 'job_faq';

    protected $fillable = [
        'id','question','answer', 'type', 'row_status'
    ];
}