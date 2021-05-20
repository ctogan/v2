<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FlashEvent extends Model
{
    protected $connection = 'common';
    protected $table = 'flash_events';

    protected $appends = array('event_start','event_end','status');

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

    public function getStatusAttribute()
    {
        $today = Carbon::now();
        $status = 'waiting';
        if($this->attributes['event_period'] == 'weekly'){
            $date = date('Y-m-d');
            $d = new \DateTime($date);
            $day_name = $d->format('l');
            if($day_name == $this->attributes['day_name']){
                $start = Carbon::parse(date_format(date_create(date('Y-m-d') .' '. $this->attributes['time_from']),"Y-m-d H:i"));
                $end = Carbon::parse(date_format(date_create(date('Y-m-d') .' '. $this->attributes['time_to']),"Y-m-d H:i"));
                if(($today->gte($start) && $today->lte($end))){
                    $status = 'running';
                }
            }
        }elseif ($this->attributes['event_period'] == 'special_date'){
            $start = Carbon::parse(date_format(date_create($this->attributes['date_from'] .' '. $this->attributes['time_from']),"Y-m-d H:i"));
            $end = Carbon::parse(date_format(date_create($this->attributes['date_to'] .' '. $this->attributes['time_to']),"Y-m-d H:i"));
            if($today->gte($start) && $today->lte($end)){
                $today_start = Carbon::parse(date_format(date_create(date('Y-m-d') .' '. $this->attributes['time_from']),"Y-m-d H:i"));
                $today_end = Carbon::parse(date_format(date_create(date('Y-m-d') .' '. $this->attributes['time_to']),"Y-m-d H:i"));
                if(($today->gte($today_start) && $today->lte($today_end))){
                    $status = 'running';
                }
            }else{
                if($today->gte($end)){
                    $status = 'expired';
                }
            }
        }else{
            $start = Carbon::parse(date_format(date_create(date('Y-m-d') .' '. $this->attributes['time_from']),"Y-m-d H:i"));
            $end = Carbon::parse(date_format(date_create(date('Y-m-d') .' '. $this->attributes['time_to']),"Y-m-d H:i"));
            if($today->gte($start) && $today->lte($end)){
                $status = 'running';
            }
        }

        return $status;
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