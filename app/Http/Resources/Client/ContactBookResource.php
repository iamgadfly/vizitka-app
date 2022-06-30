<?php

namespace App\Http\Resources\Client;

use App\Helpers\CardBackgroundHelper;
use App\Http\Resources\BusinessCardResource;
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
        if (!is_null($this->specialist)) {
            return BusinessCardResource::make($this->specialist->card);
        } else {
            return [
                'name' => $this->name,
                'surname' => $this->surname,
                'title' => $this->title,
                'about' => $this->about,
                'phone_number' => $this->phone_number,
                'avatar' => $this->avatar?->url,
                'card' => CardBackgroundHelper::getCardFromActivityKind('default')
            ];
        }
    }
}
