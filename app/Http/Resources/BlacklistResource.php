<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class BlacklistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (is_null($this->dummyClient)) {
            return [
                'id' => $this->client?->id,
                'name' => $this->client?->name,
                'surname' => $this->client?->surname,
                'phone' => $this->client?->user->phone_number,
                'avatar' => !is_null($this->dummyClient->avatar)
                    ? ImageHelper::getAssetFromFilename($this->dummyClient?->avatar?->url)
                    : $this->dummyClient->content_url,

                'type' => 'client'
            ];
        } else {
            return [
                'id' => $this->dummyClient?->id,
                'name' => $this->dummyClient?->name,
                'surname' => $this->dummyClient?->surname,
                'full_name' => $this->dummyClient?->name . " " . $this->dummyClient?->surname,
                'discount' => [
                    'label' => str( $this->dummyClient?->discount * 100)->value(),
                    'value' => (float)$this->dummyClient?->discount
                ],
                'phone_number' => $this->dummyClient->phone_number,
                'avatar' => ImageHelper::getAssetFromFilename($this->dummyClient?->avatar?->url),
                'notes' => $this->dummyClient?->notes,
                'type' => 'dummy'
            ];
        }
    }
}
