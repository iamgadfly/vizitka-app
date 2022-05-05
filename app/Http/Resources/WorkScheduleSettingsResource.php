<?php

namespace App\Http\Resources;

use App\Repositories\WorkScheduleBreakRepository;
use App\Repositories\WorkScheduleWorkRepository;
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
            'limit_before' => $this->limit_before,
            'limit_after' => $this->limit_after,
            'type' => $this->type,
            'break_type' => $this->break_type,
            'schedules' => [
                'work' => WorkScheduleWorkResource::collection(WorkScheduleWorkRepository::getWorks($this->id)),
                'breaks' => WorkScheduleBreakResource::collection(WorkScheduleBreakRepository::getBreaks($this->id))
            ],
            'specialist' => SpecialistResource::make($this->specialist)
        ];
    }
}
