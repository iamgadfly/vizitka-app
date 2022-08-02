<?php

namespace App\Http\Resources\WorkScheduleSettings;

use App\Repositories\WorkSchedule\WorkScheduleBreakRepository;
use Carbon\Carbon;
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
        $breakType = $this->day->settings->break_type;
        return [
            'day' => (string) DayResource::make($this)->day->day_index,
            'workTime' => [
                'start' => !is_null($this->start) ? Carbon::parse($this->start)->format('H:i') : null,
                'end' => !is_null($this->end) ? Carbon::parse($this->end)->format('H:i') : null
            ],
            'breaks' => !is_null($this->start) && $breakType == 'individual'
                ? FlexibleBreakResource::collection($break) : []
        ];
    }
}
