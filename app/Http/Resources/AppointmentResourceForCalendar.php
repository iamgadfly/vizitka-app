<?php

namespace App\Http\Resources;

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
            'workSchedule' => [
                'start' => $this->workSchedule->start,
                'end' => $this->workSchedule->end,
                'interval' => 15
            ]
        ];
    }
}
