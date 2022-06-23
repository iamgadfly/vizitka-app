<?php

namespace App\Http\Resources\WorkScheduleSettings;

use App\Helpers\TranslationHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeResource extends JsonResource
{
    protected ?int $value;
    public function __construct($value)
    {
        parent::__construct($value);
        $this->value = $value;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'label' => __(TranslationHelper::getTranslationForTime($this->value)),
            'value' => $this->value
        ];
    }
}
