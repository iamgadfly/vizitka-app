<?php

namespace App\Http\Resources\Appointment;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DuplicateResource extends JsonResource
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
            'service' => $this->maintenance->title,
            'date' => $this->date,
            'status' => $this->status,
            'time' => [
                'start' => Carbon::parse($this->time_start)->format('H:i'),
                'end' => Carbon::parse($this->time_end)->format('H:i'),
            ]
        ];
    }
}
