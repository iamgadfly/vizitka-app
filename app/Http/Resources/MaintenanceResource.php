<?php

namespace App\Http\Resources;

use App\Helpers\TranslationHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
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
            'title' => $this->title,
            'price' => [
                'label' => $this->price == 0 ? '' : str($this->price)->value() . ' ₽',
                'value' => $this->price
            ],
            'duration' => [
                'label' => TranslationHelper::getDurationTicsToString($this->duration),
                'value' => $this->duration
            ],
        ];
    }
}
