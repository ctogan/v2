<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobNotification extends Model
{
    protected $connection = 'common';
    protected $table = 'job_notification';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = [
        'uid','title','message','is_read', 'type','created_at','created_by','updated_at','updated_by'
    ];


}
