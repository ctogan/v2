<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FlashEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'event_code' => $this->event_code,
            'event_name' => $this->event_name,
            'event_description' => $this->event_description,
            'event_img' => $this->event_img,
            'event_tnc' => $this->event_tnc,
            'event_start' => $this->event_start,
            'event_end' => $this->event_end,
            'detail' => FlashEventDetailResource::collection($this->detail),
        ];

        // $item->event_start = date("Y-m-d H:i:s", strtotime('+5 hours'));
        // $item->event_end = date("Y-m-d H:i:s", strtotime('+5 hours'));
    }
}