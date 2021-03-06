<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsDetailResource extends JsonResource
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
            'news_code' => $this->news_code,
            'title' => $this->title,
            'author' => $this->author,
            'url_to_image' => $this->url_to_image,
            'content' => htmlspecialchars_decode(nl2br($this->content)),
            'view_count' => $this->view_count,
            'reward' => $this->reward
        ];
    }
}
