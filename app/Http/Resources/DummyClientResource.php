<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DummyClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'full_name' => "$this->name $this->surname",
            'discount' => $this->discount,
            'phone_number' => $this->phone_number,
            'avatar' => ImageResource::make($this->avatar)
        ];
    }
}
