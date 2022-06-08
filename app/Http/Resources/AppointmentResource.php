<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
use App\Helpers\TimeHelper;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'order_number' => $this->order_number,
            'services' => [
                'interval' => TimeHelper::getTimeInterval($this->time_start, $this->time_end),
                'status' => $this->status,
                'service' => $this->maintenance->title,
            ],
            'client' => [
                'name' => $this->client?->name ?? $this->dummyClient?->name,
                'surname' => $this->client?->surname ?? $this->dummyClient?->surname,
                'phone_number' => $this->client?->user->phone_number ?? $this->dummyClient?->phone_number,
                'photo' => ImageHelper::getAssetFromFilename($this->client?->avatar->url
                    ?? $this->dummyClient?->avatar->url),
                'discount' => $this?->dummyClient?->discount * 100 ?? null
            ]
        ];
    }
}
