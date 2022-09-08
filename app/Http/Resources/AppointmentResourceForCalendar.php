<?php

namespace App\Http\Resources;

use App\DataObject\AppointmentForCalendarData;
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
        $timeInterval = TimeHelper::getTimeInterval(ConstantHelper::DAY_START, ConstantHelper::DAY_END);
        $timeInterval[] = "23:59";
        /** @var AppointmentForCalendarData $this **/
        return [
            'smart_schedule' => $this->smartSchedule,
            'confirmation' => $this->confirmation,
            'data' => $this->appointments,
            'workSchedule' => $this->workSchedule,
            'disabledPills' => $this->disabled,
            'time_interval' => $timeInterval
        ];
    }
}
