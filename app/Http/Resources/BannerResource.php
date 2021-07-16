<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'banner_name' => $this->banner_name,
            'img' => $this->img,
            'deeplink' => $this->deeplink,
            'sequence' => $this->sequence,
        ];
    }
}
