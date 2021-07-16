<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $connection = 'notification';

    protected $table = 'notifications';

    protected $fillable = [
        'title','deeplink','body','row_status' ,'img' ,'send_to','send_by','topic','created_at','updated_at','created_by','updated_by'
    ];

    public function detail(){
        return $this->hasMany(NotificationDetail::class,'notification_id');
    }
}