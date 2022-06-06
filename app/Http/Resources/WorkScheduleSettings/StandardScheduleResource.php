<?php

namespace App\Http\Resources\WorkScheduleSettings;

use App\Http\Resources\WorkScheduleBreakResource;
use App\Repositories\WorkScheduleBreakRepository;
use App\Repositories\WorkScheduleWorkRepository;
use Carbon\Carbon;
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
        $break = WorkScheduleBreakRepository::getBreaks($this->id)->first();
        return [
            'weekends' => DayResource::collection($work),
            'workTime' => [
                'start' => Carbon::parse($work->first()->start)->format('H:i'),
                'end' => Carbon::parse($work->first()->end)->format('H:i')
            ],
            'breaks' => [
                'start' => Carbon::parse($break->first()->start)->format('H:i'),
                'end' => Carbon::parse($break->first()->end)->format('H:i')
            ],
        ];
    }
}
