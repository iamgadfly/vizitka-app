<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\SpecialistResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SpecialistResource::make($this->specialist);
    }
}
