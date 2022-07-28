<?php

namespace App\Http\Resources\WorkScheduleSettings;

use App\Repositories\WorkSchedule\WorkScheduleBreakRepository;
use Carbon\Carbon;
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
                'start' => Carbon::parse($this->start)->format('H:i'),
                'end' => Carbon::parse($this->end)->format('H:i')
            ],
            'breaks' => !is_null($this->start) ? FlexibleBreakResource::collection($break) : []
        ];
    }
}
