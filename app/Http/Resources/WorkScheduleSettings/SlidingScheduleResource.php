<?php

namespace App\Http\Resources\WorkScheduleSettings;

use App\Repositories\WorkSchedule\WorkScheduleBreakRepository;
use App\Repositories\WorkSchedule\WorkScheduleWorkRepository;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SlidingScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->type != 'sliding') {
            return [];
        }
        $work = WorkScheduleWorkRepository::getWorks($this->id);
        return [
            'startFrom' => [
                'label' => Carbon::parse($this->start_from)->format('d.m.Y'),
                'value' => Carbon::parse($this->start_from)->format('Y-m-d')
            ],
            'workdaysCount' => $this->workdays_count,
            'weekdaysCount' => $this->weekdays_count,
            'data' => SlidingDayResource::collection($work),
        ];
    }
}
