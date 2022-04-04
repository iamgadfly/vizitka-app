<?php

namespace App\Helpers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestHelper
{
    public static function getSpecialistCreateOrUpdateRules(FormRequest $request): array
    {
        if ($request->method() == 'POST') {
            return [
                'user_id' => 'required|int|exists:users,id',
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'avatar_id' => 'exists:images,id',
                'activity_kind_id' => 'required|int|exists:activity_kinds,id',
                'title' => 'required|string',
                'about' => 'required|string',
                'address' => 'required|string',
                'placement' => 'string',
                'floor' => 'string',
                'instagram_account' => 'string',
                'youtube_account' => 'string',
                'vk_account' => 'string',
                'tiktok_account' => 'string',
                'background_image' => ['string', Rule::in(CardBackgroundHelper::$files)]
            ];
        }
        return [
            'id' => 'required|exists:specialists,id',
            'name' => 'string|max:255',
            'surname' => 'string|max:255',
            'avatar_id' => 'exists:images,id',
            'activity_kind_id' => 'int|exists:activity_kinds,id',
            'title' => 'string',
            'about' => 'string',
            'address' => 'string',
            'placement' => 'string',
            'floor' => 'string',
            'instagram_account' => 'string',
            'youtube_account' => 'string',
            'vk_account' => 'string',
            'tiktok_account' => 'string',
            'background_image' => ['string', Rule::in(CardBackgroundHelper::$files)]
        ];
    }
}
