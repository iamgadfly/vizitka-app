<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'time_start' => Carbon::parse($this->time_start)->format('H:i'),
            'time_end' => Carbon::parse($this->time_end)->format('H:i'),
            'date' => Carbon::parse($this->date)->format('d.m.Y'),
            'status' => $this->status,
            'maintenance' => MaintenanceResource::make($this->maintenance),
            'client' => $this->when(!is_null($this->client), ClientResource::make($this->client)),
            'dummy_client' => $this->when(!is_null($this->dummyClient), DummyClientResource::make($this->dummyClient)),
        ];
    }
}
