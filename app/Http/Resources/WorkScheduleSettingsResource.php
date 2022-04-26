<?php

namespace App\Http\Resources;

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
            'smart_schedule' => $this->smart_schedule,
            'confirmation' => $this->confirmation,
            'cancel_appointment' => $this->cancel_appointment,
            'new_appointment_not_before_than' => $this->new_appointment_not_before_than,
            'new_appointment_not_after_than' => $this->new_appointment_not_after_than,
            'weekends' => json_decode($this->weekends),
            'type' => WorkScheduleTypeResource::make($this->type),
            'schedules' => WorkScheduleResource::collection($this->schedule),
            'specialist' => SpecialistResource::make($this->specialist)
        ];
    }
}
