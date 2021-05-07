<?php

namespace App\Http\Resources;

use App\CCSessionPrize;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CCSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $today = Carbon::now();
        $start = Carbon::parse(date_format(date_create($this->open_date .' '. $this->time_start),"Y-m-d H:i"));
        $end = Carbon::parse(date_format(date_create($this->open_date .' '. $this->time_end),"Y-m-d H:i"));
        $status = 'close';
        if($today->gte($end)){
            $status = 'expired';
        }else{
            if($today->gte($start) && $today->lte($end)){
                $status = 'active';
            }
        }
        return [
            'session_code' =>$this->session_code,
            'title' => $this->title,
            'registration_fee' => $this->registration_fee,
            'tnc' => $this->tnc,
            'start_date' => $this->open_date . ' ' . $this->time_start,
            'end_date' => $this->open_date . ' ' . $this->time_end,
            'question' => $this->displayed_question,
            'status' => $status,
            'is_registered' => $this->participant_count > 0 ? true : false,
            'prize' => CCSessionPrizeResource::collection($this->prize)
        ];
    }
}
