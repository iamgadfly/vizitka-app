<?php

namespace App\Http\Resources;

use App\Enums\ActivityKind;
use App\Helpers\CardBackgroundHelper;
use App\Helpers\ImageHelper;
use App\Models\Specialist;
use App\Models\WorkScheduleSettings;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialistDetailedDataResource extends JsonResource
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
        $activityKind = str(str($this->card->background_image)->explode('/')[2])->replace('.jpg', '');
        $colors = CardBackgroundHelper::getSpecialistColorFromActivityKind($activityKind);
        $settings = WorkScheduleSettings::where(['specialist_id' => $this->id])->first();
        return [
            'id' => $this->id,
            'photo' => !is_null($this->avatar) ? ImageHelper::getAssetFromFilename($this?->avatar?->url): null,
            'name' => $this->name,
            'surname' => $this->surname,
            'phoneNumber' => $this->user->phone_number,
            'activity_kind' => $this->activity_kind->name,
            'description' => $this->card->about,
            'gradientColor' => $colors['gradientColor'],
            'textColor' => $colors['textColor'],
            'buttonsColor' => $colors['buttonsColor'],
            'background_image' => ImageHelper::getAssetFromFilename($this->card->background_image),
            'confirmation' => $settings->confirmation,
            'coordiantes' => [
                'latitude' => $this->card->latitude,
                'longitude' => $this->card->longitude
            ],
            'suggestedDaysAndTime' => []
        ];
    }
}
