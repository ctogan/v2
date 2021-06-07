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
        $countdown = Carbon::parse(date_format(date_create($this->open_date .' '. $this->time_start),"Y-m-d H:i"));
        $status = 'waiting';
        if($today->gte($end)){
            $status = 'expired';
            $countdown = Carbon::parse(date_format(date_create($this->open_date .' '. $this->time_start),"Y-m-d H:i"));
        }else{
            if($today->gte($start) && $today->lte($end)){
                $status = 'active';
                $countdown = Carbon::parse(date_format(date_create($this->open_date .' '. $this->time_end),"Y-m-d H:i"));
            }
        }
        $participant_status = 'pending';
        if(count($this->participant) > 0){
            $participant_status = $this->participant[0]->row_status;
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
            'participant_status'=>$participant_status,
            'is_registered' => count($this->participant) > 0 ? true : false,
            'prize' => CCSessionPrizeResource::collection($this->prize),
            'countdown' => $countdown
        ];
    }
}
