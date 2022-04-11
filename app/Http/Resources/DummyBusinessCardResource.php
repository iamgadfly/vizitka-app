<?php

namespace App\Http\Resources;

use App\Helpers\CardBackgroundHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class DummyBusinessCardResource extends JsonResource
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
            'name' => $this->name,
            'surname' => $this->surname,
            'avatar' => ImageResource::make($this?->avatar),
            'title' => $this->title,
            'background_image' => ImageHelper::getAssetFromFilename(
                CardBackgroundHelper::filenameFromActivityKind('default')
            ),
        ];
    }
}
