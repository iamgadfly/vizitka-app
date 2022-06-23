<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
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
        if ($this->type == 'dummy') {
            return [
                'id' => $this->id,
                'client' => [
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'full_name' => "$this->name $this->surname",
                    'discount' => $this->discount * 100,
                    'phone_number' => $this->phone_number,
                    'avatar' => ImageHelper::getAssetFromFilename($this->avatar?->url),
                    'type' => $this->type
                ],
                'specialist' => SpecialistResource::make($this->specialist)
            ];
        }
        return [
            'id' => $this->id,
            'client' => [
                'id' => $this->client->id,
                'name' => $this->client->name,
                'surname' => $this->client->surname,
                'phone' => $this->client->user->phone_number,
                'avatar' => $this->client?->avatar?->url ?? null,
                'type' => $this->type
            ],
            'specialist' => SpecialistResource::make($this->specialist)
        ];
    }
}
