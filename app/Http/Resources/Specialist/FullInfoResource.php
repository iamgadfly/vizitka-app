<?php

namespace App\Http\Resources\Specialist;

use App\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class FullInfoResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'phone' => $this->user->phone_number,
            'avatar' => !is_null($this->avatar) ? ImageHelper::getAssetFromFilename($this?->avatar?->url): null,
            'activity_kind' => [
                'id' => $this->activity_kind?->id,
                'label' => $this->activity_kind?->name
            ],
            'youtube_account' => $this->youtube_account,
            'vk_account' => $this->vk_account,
            'tiktok_account' => $this->tiktok_account,
            'title' => $this->card->title,
            'about' => $this->card->about,
            'address' => $this->card->address,
            'placement' => $this->card->placement,
            'floor' => $this->card->floor,
            'background_image' => ImageHelper::getAssetFromFilename(
                $this->card->background_image
            ),
        ];
    }
}
