<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobEmployeeSize extends Model
{
    protected $connection = 'common';
    protected $table = 'job_employee_size';

    protected $fillable = [
        'id','employee_size'
    ];
}
