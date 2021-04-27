<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FlashEvent extends Model
{
    protected $connection = 'common';
    protected $table = 'flash_events';

    protected $appends = array('event_start','event_end');

    public function getEventStartAttribute()
    {
        if($this->attributes['event_period'] == 'daily'){
            return Carbon::parse(date_format(date_create(date('Y-m-d') .' '. $this->attributes['time_from']),"Y-m-d H:i"));
        }elseif ($this->attributes['event_period'] == 'special_date'){
            return Carbon::parse(date_format(date_create($this->attributes['date_from'] .' '. $this->attributes['time_from']),"Y-m-d H:i"));
        }else{
            return Carbon::parse(date_format(date_create(date('Y-m-d') .' '. $this->attributes['time_from']),"Y-m-d H:i"));
        }
    }

    public function getEventEndAttribute()
    {
        if($this->attributes['event_period'] == 'daily'){
            return Carbon::parse(date_format(date_create(date('Y-m-d') .' '. $this->attributes['time_to']),"Y-m-d H:i"));
        }elseif ($this->attributes['event_period'] == 'special_date'){
            return Carbon::parse(date_format(date_create($this->attributes['date_to'] .' '. $this->attributes['time_to']),"Y-m-d H:i"));
        }else{
            return Carbon::parse(date_format(date_create(date('Y-m-d') .' '. $this->attributes['time_to']),"Y-m-d H:i"));
        }
    }

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
        return $this->hasMany(FlashEventDetail::class, 'flash_event_id')
            ->with('product')
            ->where('row_status','!=', 'deleted');
    }
}