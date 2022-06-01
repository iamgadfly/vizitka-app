<?php

namespace App\Helpers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestHelper
{
    public static function getMaintenanceRules(FormRequest $request): array
    {
        return [
            'finance_analytics' => ['required', 'boolean', 'bail'],
            'many_maintenances' => ['required', 'boolean', 'bail'],
            'specialist_id' => ['required' , 'exists:specialists,id', 'bail'],
            'maintenances' => ['required', 'array', 'bail'],
            'maintenances.*.title' => ['required', 'string', 'bail'],
            'maintenances.*.price' => ['required', 'integer', 'bail'],
            'maintenances.*.duration' => ['required', 'integer', 'bail']
        ];
    }

    public static function getWorkScheduleRules(FormRequest $request): array
    {
        $rules = [
            'schedule' => ['required', 'array'],
            'schedule.type' => ['required', Rule::in(WorkScheduleTypeHelper::getAllKeys()), 'bail'],
            'schedule.break_type' => ['required', 'string', Rule::in(WorkScheduleTypeHelper::getBreakTypes()), 'bail'],
            'schedule.smart_schedule' => ['required', 'boolean', 'bail'],
            'schedule.confirmation' => ['required', 'boolean', 'bail'],
            'schedule.cancel_appointment' => ['required', 'integer', 'bail'],
            'schedule.limit_before' => ['required', 'integer', 'bail'],
            'schedule.limit_after' => ['required', 'integer', 'bail'],
            'schedule.workdays_count' => ['integer', 'bail', 'required_if:type,==,sliding'],
            'schedule.weekends_count' => ['integer', 'bail', 'required_if:type,==,sliding'],
            'schedule.start_from' => ['date_format:d.m.Y', 'bail', 'required_if:type,==,sliding'],
            'schedule.schedules' => ['required', 'array', 'bail']
        ];
        if ($request->schedule['type'] == 'sliding') {
            $rules = array_merge($rules, self::getSlidingScheduleRules());
        } else {
            $rules = array_merge($rules, self::getNotSlidingScheduleRules());
        }

        return $rules;
    }

    public static function getAppointmentRules(FormRequest $request): array
    {
        $rules = [
            'client_id' => ['exclude_if:dummy_clients_id,!=,null', 'exists:clients,id', 'bail'],
            'dummy_client_id' => ['exclude_if:client_id,!=,null', 'exists:dummy_clients,id', 'bail'],
            'specialist_id' => ['required', 'exists:specialists,id', 'bail'],
            'appointments' => ['required', 'array'],
            'appointments.*.maintenance_id' => ['required', 'exists:maintenances,id', 'bail'],
            'appointments.*.date' => ['required', 'date_format:Y-m-d', 'bail'],
            'appointments.*.time_start' => ['required', 'date_format:H:i', 'bail'],
            'appointments.*.time_end' => ['required', 'date_format:H:i', 'bail', 'after:time_start'],
        ];

        if ($request->method() == 'PUT') {
            $rules['order_number'] = ['required', 'exists:appointments,order_number', 'bail'];
            $rules['appointments.*.id'] = ['required', 'exists:appointments,id', 'bail'];
        }

        return $rules;
    }

    public static function getDummyClientRules(FormRequest $request): array
    {
        $rules = [
            'name' => ['string', 'bail'],
            'surname' => ['string', 'bail'],
            'phone_number' => ['string', 'bail', 'unique:dummy_clients,phone_number'],
            'discount' => ['integer', 'min:0', 'max:100', 'bail'],
            'avatar_id' => ['exists:images,id', 'bail']
        ];
        if ($request->method() == 'POST') {
            $rules['name'][] = 'required';
            $rules['surname'][] = 'required';
            $rules['phone_number'][] = 'required';
            $rules['discount'][] = 'required';
            $rules['avatar_id'][] = 'required';
            $rules['specialist_id'] = ['required', 'exists:specialists,id', 'bail'];
        } elseif ($request->method() == 'PUT') {
            $rules['id'] = ['required', 'exists:dummy_clients,id', 'bail'];
        }
        return $rules;
    }

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
            'avatar_id' => ['integer', 'exists:images,id'],
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
            $rules['activity_kind_id'][] = 'required';
            $rules['title'][] = 'required';
        } else {
            $rules['id'] = ['required', 'exists:specialists,id'];
        }

        return $rules;
    }

    private static function getNotSlidingScheduleRules(): array
    {
        $rules = [];
        // Standard schedules
        $rules['schedule.schedules.work'] = ['array', 'bail', 'max:7', 'required_if:type,!=,sliding'];
        $rules['schedule.schedules.work.*.day'] = [
            Rule::in(WeekdayHelper::getAll()), 'string', 'bail', 'required_if:type,!=,sliding'
        ];
        $rules['schedule.schedules.work.*.start'] = [
            'date_format:H:i', 'bail', 'nullable', 'required_if:schedules.work.*.is_weekend,false'
        ];
        $rules['schedule.schedules.work.*.end'] = [
            'date_format:H:i', 'bail', 'nullable', 'required_if:schedules.work.*.is_weekend,false'
        ];
        // Standard breaks
        $rules['schedule.schedules.breaks'] = ['array', 'bail', 'required_if:type,!=,sliding'];
        $rules['schedule.schedules.breaks.*.day'] = [Rule::in(WeekdayHelper::getAll()), 'string', 'bail'];
        $rules['schedule.schedules.breaks.*.start'] = ['date_format:H:i', 'bail'];
        $rules['schedule.schedules.breaks.*.end'] = ['date_format:H:i', 'bail'];

        return $rules;
    }

    private static function getSlidingScheduleRules(): array
    {
        return [
            // Sliding schedules
            'schedule.schedules.work.*.day' => ['string', 'bail', 'required_if:type,==,sliding'],
            'schedule.schedules.work.*.start' => [
                'date_format:H:i', 'bail', 'nullable', 'required_if:schedules.work.*.is_weekend,false'
            ],
            'schedule.schedules.work.*.end' => [
                'date_format:H:i', 'bail', 'nullable', 'required_if:schedules.work.*.is_weekend,false'
            ],
            // Sliding breaks
            'schedule.schedules.breaks' => ['required_if:type,sliding', 'array', 'bail'],
            'schedule.schedules.breaks.*.day' => ['integer', 'bail'],
            'schedule.schedules.breaks.*.start' => ['date_format:H:i', 'bail'],
            'schedule.schedules.breaks.*.end' => ['date_format:H:i', 'bail'],
        ];
    }
}
