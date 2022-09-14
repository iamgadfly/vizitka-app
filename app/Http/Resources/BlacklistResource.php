<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
use App\Models\Blacklist;
use App\Models\Client;
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
            /** @var Blacklist $this **/
            $contactData = $this->client->contactData();
            return [
                'id' => $this->client?->id,
                'name' => !is_null($contactData?->name) ? $contactData->name : $this->client?->name,
                'surname' => !is_null($contactData?->surname) ? $contactData->surname : $this->client?->surname,
                'phone' => !is_null($contactData?->phone_number) ? $contactData->phone_number : $this->client?->user->phone_number,
                'avatar' => !is_null($this->client?->avatar)
                    ? ImageHelper::getAssetFromFilename($this->client?->avatar?->url)
                    : null,
                'discount' => [
                    'label' => str($contactData?->discount * 100)->value(),
                    'value' => $contactData?->discount
                ],
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
