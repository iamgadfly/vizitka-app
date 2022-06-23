<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceSettingsResource extends JsonResource
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
            'finance_analytics' => $this->finance_analytics,
            'many_maintenances' => $this->many_maintenances,
            'maintenances' => MaintenanceResource::collection($this->maintenances)
        ];
    }
}
