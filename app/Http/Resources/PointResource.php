<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return [
        //     'id'=>$this->id,
        //     'title' => $this->title,
        //     'deeplink' => $this->deeplink,
        //     'body' => $this->body,
        //     'img'=> $this->img,
        //     'is_read'=>$is_read
        // ];
    }
}
