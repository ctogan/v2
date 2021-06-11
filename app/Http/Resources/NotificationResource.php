<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $is_read = false;
        if(count($this->detail) > 0){
            $is_read= $this->detail[0]->is_read;
        }
        return [
            'id'=>$this->id,
            'title' => $this->title,
            'deeplink' => $this->deeplink,
            'body' => $this->body,
            'img'=> $this->img,
            'is_read'=>$is_read,
            'tm'=> date_format($this->created_at,'Y-m-d H:i:s')
        ];
    }
}
