<?php

namespace App\Http\Resources;

use App\Helpers\CardBackgroundHelper;
use App\Helpers\ImageHelper;
use App\Http\Resources\Specialist\FullInfoResource;
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
            return FullInfoResource::make($this->specialist);
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
            'background_image' => [
                'url' => ImageHelper::getAssetFromFilename(
                    'images/card_backgrounds/default.jpg'
                ),
                'value' => CardBackgroundHelper::getActivityKindFromFilename(
                    'images/card_backgrounds/default.jpg'
                )
            ],
            'card' => CardBackgroundHelper::getSpecialistColorFromActivityKind('psychology_2')
        ];
    }
}
