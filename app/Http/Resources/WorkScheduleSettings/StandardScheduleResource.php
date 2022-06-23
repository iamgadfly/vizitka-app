<?php

namespace App\Http\Resources\WorkScheduleSettings;

use App\Repositories\WorkSchedule\WorkScheduleBreakRepository;
use App\Repositories\WorkSchedule\WorkScheduleWorkRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class StandardScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->type != 'standard') {
            return [];
        }
        $work = WorkScheduleWorkRepository::getWorks($this->id);
        $weekends = WorkScheduleWorkRepository::getWeekends($this->id);
        $break = WorkScheduleBreakRepository::getBreaks($this->id)->first();
        return [
            'weekends' => !is_null($work) ? DayResource::collection($weekends) : [],
            'workTime' => !is_null($work?->first())
                ? WorkTimeResource::make($work->first()->start, $work->first()->end)
                : [],
            'breaks' => !is_null($break) ? WorkTimeResource::make($break->start, $break->end) : [],
        ];
    }
}
