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
        $breakType = $this->day->settings->break_type;
        $break = WorkScheduleBreakRepository::getBreaksForADayIndex($this->day->day_index);
        return [
            'day' => (string) DayResource::make($this)->day->day_index,
            'workTime' => [
                'start' => Carbon::parse($this->start)->format('H:i'),
                'end' => Carbon::parse($this->end)->format('H:i')
            ],
            'breaks' => !is_null($this->start) && $breakType == 'individual'
                ? FlexibleBreakResource::collection($break) : null
        ];
    }
}
