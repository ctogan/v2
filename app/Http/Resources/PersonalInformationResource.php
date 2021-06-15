<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonalInformationResource extends JsonResource
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
            'uid'=>$this->uid,
            'code_id'=>$this->code ? $this->code : '',
            'code'=>$this->code_name ? $this->code_name : '',
            'value'=>$this->value_name ? $this->value_name : ''
        ];
    }
}
