<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobBookmark extends Model
{
    protected $connection = 'common';
    protected $table = 'job_bookmark';

    protected $fillable = [
        'row_status','uid','vacancy_id','created_at','created_by','updated_at','updated_by'
    ];
}
