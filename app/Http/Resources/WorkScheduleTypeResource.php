<?php

namespace App\Http\Resources;

use App\Helpers\WorkScheduleTypeHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkScheduleTypeResource extends JsonResource
{
    public static $wrap = null;
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
            'type' => WorkScheduleTypeHelper::get($this->name)
        ];
    }
}
