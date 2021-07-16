<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CCSessionPrizeResource extends JsonResource
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
            'rank' => $this->rank,
            'img' => $this->product->img,
            'prize_name' => $this->product->product_name,
        ];
    }
}
