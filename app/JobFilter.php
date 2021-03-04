<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobFilter extends Model
{
    protected $connection = 'common';
    protected $table = 'job_filter';

    protected $fillable = [
        'id','uid','filter','filter_text'
    ];
}
