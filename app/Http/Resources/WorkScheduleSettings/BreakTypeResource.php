<?php

namespace App\Http\Resources\WorkScheduleSettings;

use Illuminate\Http\Resources\Json\JsonResource;

class BreakTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->type == 'standard') {
            $this->break_type = 'united';
        }
        return [
            'label' => __('workSchedule.settings.breakType.' . $this->break_type),
            'value' => $this->break_type
        ];
    }
}
