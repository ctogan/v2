<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashEventDetail extends Model
{
    protected $connection = 'common';
    protected $table = 'flash_event_details';

    protected $fillable = [
        'row_status','flash_detail_code','flash_event_id','product_id','point','cap','updated_by','updated_at'
    ];

    public function flash_event(){
        return $this->belongsTo(FlashEvent::class, 'flash_event_id')->where('row_status','!=', 'deleted');
    }
}