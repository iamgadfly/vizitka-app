<?php

namespace App\Http\Resources;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\ImageHelper;
use App\Models\Client;
use App\Models\ContactBook;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     * @throws SpecialistNotFoundException
     */
    public function toArray($request)
    {
        $type = is_null($this->dummyClient) ? 'client' : 'dummy';
        if ($type == 'dummy') {
            return [
                'id' => $this->dummyClient?->id,
                'name' => $this->dummyClient?->name,
                'surname' => $this->dummyClient?->surname,
                'discount' => [
                    'label' => str($this->dummyClient?->discount * 100)->value(),
                    'value' => (float)$this->dummyClient?->discount
                ],
                'notes' => $this->dummyClient?->notes,
                'phone_number' => $this->dummyClient?->phone_number,
//                'avatar' => ImageHelper::getAssetFromFilename($this->dummyClient?->avatar?->url),
//TODO: uncomment this in future, when we'll find solution
                'avatar' => !is_null($this->dummyClient->avatar)
                    ? ImageHelper::getAssetFromFilename($this->dummyClient?->avatar?->url)
                    : $this->dummyClient->content_url,

                'type' => $type
            ];
        }
        /** @var ContactBook $this**/
        $contactData = $this->client->contactData();
        return [
            'id' => $this->client->id,
            'name' => !is_null($contactData?->name) ? $contactData->name : $this->client->name,
            'surname' => !is_null($contactData?->surname) ? $contactData->surname : $this->client->surname,
            'phone_number' => !is_null($contactData?->phone_number) ? $contactData?->phone_number : $this->client->user->phone_number,
            'avatar' => ImageHelper::getAssetFromFilename($this->client?->avatar?->url),
            'type' => $type,
            'discount' => [
                'label' => str($contactData?->discount)->value(),
                'value' => (float) $contactData?->discount
            ],
            'notes' => $contactData?->notes,
        ];
    }
}
