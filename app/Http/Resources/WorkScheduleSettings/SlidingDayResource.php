<?php

namespace App\Http\Resources\WorkScheduleSettings;

use App\Repositories\WorkScheduleBreakRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class SlidingDayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $break = WorkScheduleBreakRepository::getBreaksForADayIndex($this->day->day_index);
        return [
            'day' => (string) DayResource::make($this)->day->day_index,
            'workTime' => [
                'start' => $this->start,
                'end' => $this->end
            ],
            'breaks' => !is_null($this->start) ? FlexibleBreakResource::collection($break) : null
        ];
    }
}
