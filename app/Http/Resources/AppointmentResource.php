<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
use App\Helpers\TimeHelper;
use App\Helpers\TranslationHelper;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use function PHPUnit\Framework\isInstanceOf;

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
        if ($this->resource instanceof Appointment) {
            return [
                'id' => $this->id,
                'order_number' => $this->order_number,
                'date' => $this->date,
                'services' => [
                    'id' => $this->maintenance->id,
                    'interval' => TimeHelper::getTimeInterval($this->time_start, $this->time_end),
                    'status' => $this->status,
                    'title' => $this->maintenance->title,
                    'price' => [
                        'label' => str($this->maintenance->price)->value() . ' ',
                        'value' => $this->maintenance->price
                    ],
                    'duration' => [
                        'label' => TranslationHelper::getDurationTicsToString($this->maintenance->duration),
                        'value' => $this->maintenance->duration
                    ]
                ],
                'client' => [
                    'id' => $this->client?->id ?? $this->dummyClient?->id,
                    'name' => $this->client?->name ?? $this->dummyClient?->name,
                    'surname' => $this->client?->surname ?? $this->dummyClient?->surname,
                    'phone_number' => $this->client?->user->phone_number ?? $this->dummyClient?->phone_number,
                    'photo' => ImageHelper::getAssetFromFilename($this->client?->avatar?->url
                        ?? $this->dummyClient?->avatar?->url),
                    'discount' => [
                        'label' => str( $this->dummyClient?->discount * 100)->value(),
                        'value' => (float)$this->dummyClient?->discount
                    ],
                ]
            ];
        } else {
            return [
                'id' => $this->id,
                'order_number' => '',
                'services' => [
                    'interval' => TimeHelper::getTimeInterval($this->start, $this->end),
                    'status' => 'break',
                    'service' => '',
                ],
                'client' => []
            ];
        }
    }
}
