<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecialistResource extends JsonResource
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
            'name' => $this->name,
            'surname' => $this->surname,
            'phone' => $this->user->phone_number,
            'avatar' => $this?->avatar?->url,
            'activity_kind' => $this->activity_kind?->name,
            'youtube_account' => $this->youtube_account,
            'vk_account' => $this->vk_account,
            'tiktok_account' => $this->tiktok_account,
        ];
    }
}
