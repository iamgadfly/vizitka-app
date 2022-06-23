<?php

namespace App\Http\Resources;

use App\Http\Resources\WorkScheduleSettings\BreakTypeResource;
use App\Http\Resources\WorkScheduleSettings\FlexibleScheduleResource;
use App\Http\Resources\WorkScheduleSettings\SlidingScheduleResource;
use App\Http\Resources\WorkScheduleSettings\StandardScheduleResource;
use App\Http\Resources\WorkScheduleSettings\TimeResource;
use App\Http\Resources\WorkScheduleSettings\TypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkScheduleSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => TypeResource::make($this),
            'smart_schedule' => $this->smart_schedule,
            'confirmation' => $this->confirmation,
            'cancel_appointment' => TimeResource::make($this->cancel_appointment),
            'limit_before' => TimeResource::make($this->limit_before),
            'limit_after' => TimeResource::make($this->limit_after),
            'break_type' => BreakTypeResource::make($this),
            'standardSchedule' => StandardScheduleResource::make($this),
            'flexibleSchedule' => FlexibleScheduleResource::make($this),
            'slidingSchedule' => SlidingScheduleResource::make($this),
            'specialist' => SpecialistResource::make($this->specialist)
        ];
    }
}
