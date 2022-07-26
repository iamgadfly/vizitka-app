<?php

namespace App\Http\Resources\Specialist;

use App\Helpers\CardBackgroundHelper;
use App\Helpers\ImageHelper;
use App\Models\BusinessCard;
use App\Models\Specialist;
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
        /**
         * @var Specialist $this
         */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'phone' => $this->user->phone_number,
            'avatar' => [
                'id' => !is_null($this->avatar) ? $this->avatar->id : null,
                'url' => !is_null($this->avatar) ? ImageHelper::getAssetFromFilename($this?->avatar?->url): null
            ],
            'activity_kind' => [
                'value' => $this->activity_kind?->id,
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
            'coordiantes' => [
                'latitude' => $this->card?->latitude,
                'longitude' => $this->card?->longitude
            ],
            'background_image' => [
                'url' => ImageHelper::getAssetFromFilename(
                    $this->card->background_image
                ),
                'value' => CardBackgroundHelper::getActivityKindFromFilename(
                    $this->card->background_image
                )
            ]
        ];
    }
}
