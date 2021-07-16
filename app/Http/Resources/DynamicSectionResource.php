<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DynamicSectionResource extends JsonResource
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
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'target' => $this->target,
            'url' => $this->url,
            'deeplink' => $this->deeplink,
            'dynamic_section_img' => $this->dynamic_section_img,
        ];
    }
}
