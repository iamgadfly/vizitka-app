<?php

namespace App\Helpers;

use Axiom\Rules\TelephoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestHelper
{
    public static function getDummyBusinessCardRules(FormRequest $request): array
    {
        if ($request->method() == 'POST') {
            return [
                'client_id' => 'required|exists:clients,id',
                'name' => 'required|string',
                'surname' => 'required|string',
                'title' => 'required|string',
                'about' => 'string',
                'avatar_id' => 'exists:images,id',
                'phone_number' => 'required|string|min:8|max:16'
            ];
        } elseif ($request->method() == 'PUT') {
            return [
                'id' => 'required|exists:dummy_business_cards,id',
                'name' => 'string',
                'surname' => 'string',
                'title' => 'string',
                'about' => 'string',
                'avatar_id' => 'exists:images,id',
                'phone_number' => 'string|min:8|max:16'
            ];
        }
        return [
            'id' => 'required|exists:dummy_business_cards,id'
        ];
    }

    public static function getBusinessCardHolderRules(FormRequest $request): array
    {
        // TODO: implement this
        return [
        ];
    }

    public static function getClientRules(FormRequest $request): array
    {
        if ($request->method() == 'POST') {
            return [
                'user_id' => 'required|int|exists:users,id',
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'avatar_id' => 'exists:images,id'
            ];
        }

        return [
            'id' => 'required|int|exists:clients,id',
            'name' => 'string|max:255',
            'surname' => 'string|max:255',
            'avatar_id' => 'exists:images,id'
        ];
    }

    public static function getBusinessCardRules(FormRequest $request): array
    {
        if ($request->method() == 'POST') {
            return [
                'background_image' => ['string', Rule::in(CardBackgroundHelper::$files)]
            ];
        }
        return [
            'id' => 'required|exists:business_cards,id',
            'background_image' => ['string', Rule::in(CardBackgroundHelper::$files)]
        ];
    }

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
