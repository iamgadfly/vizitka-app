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
                'phone_number' => $this->dummyClient?->phone_number,
                'avatar' => ImageHelper::getAssetFromFilename($this->dummyClient?->avatar?->url),
                'type' => $type
            ];
        }
        return [
            'id' => $this->client->id,
            'name' => $this?->contactData?->name ?? $this->client->name,
            'surname' => $this?->contactData?->surname ?? $this->client->surname,
            'phone' => $this?->contactData?->phone_number ?? $this->client->user->phone_number,
            'avatar' => $this->client?->avatar?->url ?? null,
            'type' => $type,
            'discount' => [
                'label' => str($this?->contactData?->discount)->value(),
                'value' => (float) $this?->contactData?->discount
            ],
            'notes' => $this?->contactData?->notes,
        ];
    }
}
