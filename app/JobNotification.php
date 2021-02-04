<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobNotification extends Model
{
    protected $connection = 'common';
    protected $table = 'job_notification';

    protected $fillable = [
        'uid','title','message','is_read', 'type','created_at','created_by','updated_at','updated_by'
    ];

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->getTimestamp();
    }
}
