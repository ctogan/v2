<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationDetail extends Model
{
    protected $connection = 'notification';
    protected $table = 'notification_details';
    public $timestamps = false;

    protected $fillable = [
        'notification_id','uid','is_read','created_at','updated_at'
    ];

    public function notification(){
        return $this->belongsTo(Notification::class,'notification_id');
    }
}
