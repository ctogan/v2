<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashEvent extends Model
{
    protected $connection = 'common';
    protected $table = 'flash_events';

    protected $fillable = [
        'row_status',
        'event_code',
        'event_name',
        'event_description',
        'event_period',
        'time_from',
        'day_name',
        'date_from',
        'date_to',
        'time_to',
        'event_img',
        'ut_by_register_date',
        'registered_from',
        'registered_to',
        'event_tnc',
        'ut_by_point_count',
        'target_point_from',
        'target_point_to',
        'is_tester',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    public function detail(){
        return $this->hasMany(FlashEventDetail::class, 'flash_event_id')->where('row_status','!=', 'deleted');
    }
}