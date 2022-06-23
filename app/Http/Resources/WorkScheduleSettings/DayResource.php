<?php

namespace App\Http\Resources\WorkScheduleSettings;

use Illuminate\Http\Resources\Json\JsonResource;

class DayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request)
    {
        if (is_null($this->day->day)) {
            return null;
        }
        if (!is_null($this->day->day_index)) {
            return (string) $this->day->day_index;
        }
        return [
            'label' => __('workSchedule.settings.days.'. $this->day->day),
            'cut' => __('workSchedule.settings.daysCut.'. $this->day->day),
            'value' => $this->day?->day
        ];
    }
}
