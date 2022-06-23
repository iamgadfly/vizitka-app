<?php

namespace App\Http\Resources\WorkScheduleSettings;

use App\Repositories\WorkSchedule\WorkScheduleBreakRepository;
use App\Repositories\WorkSchedule\WorkScheduleWorkRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class FlexibleScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->type != 'flexible') {
            return [];
        }
        $work = WorkScheduleWorkRepository::getWorks($this->id);
        $breaks = WorkScheduleBreakRepository::getBreaks($this->id);
        return [
            'data' => FlexibleDayResource::collection($work),
            'breakType' => BreakTypeResource::make($this),
            'breaks' => $this->break_type == 'united' ? FlexibleBreakResource::collection($breaks) : null
        ];
    }
}
