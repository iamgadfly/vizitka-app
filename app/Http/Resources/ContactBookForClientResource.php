<?php

namespace App\Http\Resources;

use App\Helpers\CardBackgroundHelper;
use App\Helpers\ImageHelper;
use App\Models\ContactBook;
use App\Models\DummyBusinessCard;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactBookForClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->resource instanceof ContactBook) {
            /**
             * @var ContactBook $this
             */
            return [
                'id' => $this->specialist->id,
                'name' => $this->specialist->name,
                'surname' => $this->specialist->surname,
                'phone_number' => $this->specialist->user->phone_number,
                'activity_kind' => [
                    'id' => $this->specialist->activity_kind->id,
                    'name' => $this->specialist->activity_kind->name,
                ],
                'avatar' => ImageHelper::getAssetFromFilename($this->specialist->avatar?->url),
                'card' => CardBackgroundHelper::getCardFromActivityKind(
                    CardBackgroundHelper::getActivityKindFromFilename($this->specialist->card->background_image)
                ),
                'colors' => CardBackgroundHelper::getSpecialistColorFromActivityKind(
                    CardBackgroundHelper::getActivityKindFromFilename($this->specialist->card->background_image)
                ),
                'title' => $this->specialist->card->title,
                'about' => $this->specialist->card->about,
                'address' => $this->specialist->card->address,
                'placement' => $this->specialist->card->placement,
                'floor' => $this->specialist->card->floor,
                'coordinates' => [
                    'latitude' => $this->specialist->card?->latitude,
                    'longitude' => $this->specialist->card?->longitude
                ],
                'share' => [
                    'link' => config('app.url') . '/shares/' . $this->specialist?->share?->hash,
                    'qr' => ImageHelper::getAssetFromFilename("images/shares/{$this->specialist?->share?->hash}.png")
                ],
            ];
        }
        /**
         * @var DummyBusinessCard $this
         */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'about' => $this->about,
            'title' => $this->title,
            'phone_number' => $this->phone_number,
            'avatar' => ImageHelper::getAssetFromFilename($this->avatar?->url),
            'card' => CardBackgroundHelper::getCardFromActivityKind('default')
        ];
    }
}
