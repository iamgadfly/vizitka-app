<?php

namespace App\Http\Resources;

use App\Helpers\CardBackgroundHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessCardResource extends JsonResource
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
            'title' => $this->title,
            'about' => $this->about,
            'address' => $this->address,
            'placement' => $this->placement,
            'floor' => $this->floor,
            'background_image' => ImageHelper::getAssetFromFilename(
                $this->background_image
            ),
            'specialist' => SpecialistResource::make($this->specialist)
        ];
    }
}
