<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'id_news' => $this->id,
            'title' => $this->title,
            'url_to_image' => $this->url_to_image,
            'reward'=> $this->news_read_count > 0 ? 0 : $this->reward
        ];
    }
}
