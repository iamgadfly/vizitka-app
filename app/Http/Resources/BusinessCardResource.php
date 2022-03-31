<?php

namespace App\Http\Resources;

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
            'id' => $this['id'],
            'card_title' => $this['card_title'],
            'specialist' => $this['specialist'],
            'about' => $this['about'],
            'phone_number' => $this['phone_number'],
            'specialist_avatar' => $this['specialist_avatar']
        ];
    }
}
