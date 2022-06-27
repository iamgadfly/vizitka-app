<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
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
            'discount' => $this->discount * 100,
            'phone_number' => $this->phone_number,
            'avatar' => ImageHelper::getAssetFromFilename($this->avatar?->url)
        ];
    }
}