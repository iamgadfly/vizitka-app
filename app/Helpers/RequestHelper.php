<?php

namespace App\Helpers;

use App\Rules\Maintenance;
use App\Rules\Weekday;
use App\Rules\WorkSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestHelper
{
    public static function getDummyBusinessCardRules(FormRequest $request): array
    {
        $rules = [
            'client_id' => ['exists:clients,id'],
            'name' => ['string'],
            'surname' => ['string'],
            'title' => ['string'],
            'about' => ['string'],
            'avatar_id' => ['exists:images,id'],
            'phone_number' => ['string|min:8|max:16']
        ];
        if ($request->method() == 'POST') {
            $rules['client_id'][] = 'required';
            $rules['name'][] = 'required';
            $rules['surname'][] = 'required';
            $rules['title'][] = 'required';
            $rules['phone_number'][] = 'required';
        } elseif ($request->method() == 'PUT') {
            $rules['id'] = ['required', 'exists:dummy_business_cards,id'];
        } else {
            $rules = [
                'id' => ['required', 'exists:dummy_business_cards,id']
            ];
        }

        return $rules;
    }

    public static function getBusinessCardHolderRules(FormRequest $request): array
    {
        // TODO: implement this
        return [];
    }

    public static function getClientRules(FormRequest $request): array
    {
        $rules = [
            'user_id' => ['int', 'exists:users,id'],
            'name' => ['string', 'max:255'],
            'surname' => ['string', 'max:255'],
            'avatar_id' => ['exists:images,id']
        ];
        if ($request->method() == 'POST') {
            $rules['user_id'][] = 'required';
            $rules['name'][] = 'required';
            $rules['surname'][] = 'required';
        } else {
            $rules['id'] = ['required','int', 'exists:clients,id'];
        }

        return $rules;
    }

    public static function getBusinessCardRules(FormRequest $request): array
    {
        $rules = [
            'background_image' => ['string', Rule::in(CardBackgroundHelper::$files)]
        ];
        if ($request->method() != 'POST') {
            $rules['id'] = ['required', 'exists:business_cards,id'];
        }
        return $rules;
    }

    public static function getSpecialistCreateOrUpdateRules(FormRequest $request): array
    {
        $rules = [
            'user_id' => ['int', 'exists:users,id'],
            'name' => ['string', 'max:255'],
            'surname' => ['string', 'max:255'],
            'avatar_id' => ['exists:images,id'],
            'activity_kind_id' => ['int', 'exists:activity_kinds,id'],
            'title' => ['string'],
            'about' => ['string'],
            'address' => ['string'],
            'placement' => ['string'],
            'floor' => ['string'],
            'instagram_account' => ['string'],
            'youtube_account' => ['string'],
            'vk_account' => ['string'],
            'tiktok_account' => ['string'],
            'background_image' => ['string', Rule::in(CardBackgroundHelper::$files)],
        ];
        if ($request->method() == 'POST') {
            $rules['user_id'][] = 'required';
            $rules['name'][] = 'required';
            $rules['surname'][] = 'required';
            $rules['activity_kind_id'][] = 'required';
            $rules['title'][] = 'required';
            $rules['about'][] = 'required';
            $rules['address'][] = 'required';
            $rules['schedule'] = [
                'smart_schedule' => ['required', 'boolean', 'bail'],
                'confirmation' => ['required', 'boolean', 'bail'],
                'cancel_appointment' => ['required', 'integer', 'bail'],
                'new_appointment_not_before_than' => ['required', 'integer', 'bail'],
                'new_appointment_not_after_than' => ['required', 'integer', 'bail'],
                'weekends' => ['required', 'array', new Weekday, 'bail'],
                'type_id' => ['required', 'integer', 'exists:work_schedule_types,id', 'bail'],
                'specialist_id' => ['required', 'integer', 'exists:specialists,id', 'bail'],
                'schedules' => ['required', 'array', new WorkSchedule, 'bail'],
            ];
            $rules['maintenance'] = [
                'finance_analytics' => ['required', 'boolean', 'bail'],
                'many_maintenances' => ['required', 'boolean', 'bail'],
                'specialist_id' => ['required' , 'exists:specialists,id', 'bail'],
                'maintenances' => ['required', 'array', new Maintenance, 'bail']
            ];
        } else {
            $rules['id'] = ['required', 'exists:specialists,id'];
        }
        return $rules;
    }
}
