<?php

namespace App\Http\Resources\WorkScheduleSettings;

use App\Repositories\WorkScheduleBreakRepository;
use App\Repositories\WorkScheduleWorkRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class FlexibleDayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $break = WorkScheduleBreakRepository::getBreaksForADay($this->day->day);
        return [
            'day' => DayResource::make($this),
            'workTime' => [
                'start' => $this->start,
                'end' => $this->end
            ],
            'breaks' => !is_null($this->start) ? FlexibleBreakResource::collection($break) : null
        ];
    }
}
