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
//            'startTime' => Carbon::parse($this->time_start)->toISOString(),
//            'endTime' => Carbon::parse($this->time_end)->toISOString(),
            'id' => $this->id,
            'interval' => TimeHelper::getTimeInterval($this->time_start, $this->time_end),
            'status' => $this->status,
            'service' => $this->maintenance->title,
            'name' => $this->client?->name ?? $this->dummyClient?->name,
            'surname' => $this->client?->name ?? $this->dummyClient?->surname,
            'photo' => ImageHelper::getAssetFromFilename($this->client?->avatar->url
                ?? $this->dummyClient?->avatar->url)
        ];
    }
}
