<?php

namespace App\Http\Resources;

use App\Helpers\ConstantHelper;
use App\Helpers\TimeHelper;
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
        $workSchedule = TimeHelper::getTimeInterval($this->workSchedule?->start, $this->workSchedule?->end);
        $workSchedule[] = "23:59";
        return [
            'smart_schedule' => $this->smartSchedule,
            'data' => $this->appointments,
            'workSchedule' => $workSchedule,
            'time_interval' => TimeHelper::getTimeInterval(ConstantHelper::DAY_START, ConstantHelper::DAY_END)
        ];
    }
}
