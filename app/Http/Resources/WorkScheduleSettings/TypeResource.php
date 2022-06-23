<?php

namespace App\Http\Resources\WorkScheduleSettings;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
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
            'label' => __('workSchedule.settings.type.' . $this->type),
            'value' => $this->type
        ];
    }
}
