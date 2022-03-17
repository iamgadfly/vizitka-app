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
            'id' => $this['id'],
            'name' => $this['name'],
            'phone' => $this['phone'],
            'about' => $this['about'],
            'avatar' => $this['avatar'],
            'activity_kind' => $this['activity_kind'],
            'address' => [
                'address' => $this['address'],
                'placement' => $this['placement'],
                'floor' => $this['floor']
            ],
            'card_title' => $this['card_title'],
            'instagram_account' => $this['instagram_account'],
            'youtube_account' => $this['youtube_account'],
            'vk_account' => $this['vk_account'],
            'tiktok_account' => $this['tiktok_account'],
        ];
    }
}
