<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FlashEventDetailResource extends JsonResource
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
            'flash_detail_code' => $this->flash_detail_code,
            'product_code' => $this->product->product_code,
            'product_name' => $this->product->product_name,
            'product_img' => $this->product->img,
            'point' => $this->point,
            'stock' => $this->cap - $this->stock,
            'cap' => $this->cap
        ];
    }
}
