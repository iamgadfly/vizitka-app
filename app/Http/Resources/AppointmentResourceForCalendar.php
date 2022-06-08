<?php

namespace App\Http\Resources;

use App\Helpers\TimeHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResourceForCalendar extends JsonResource
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
            'data' => AppointmentResource::collection($this->appointments),
            'workSchedule' => TimeHelper::getTimeInterval($this->workSchedule?->start, $this->workSchedule?->end),
            //TODO: move to constant
            'time_interval' => TimeHelper::getTimeInterval('00:00', '23:45')
        ];
    }
}
