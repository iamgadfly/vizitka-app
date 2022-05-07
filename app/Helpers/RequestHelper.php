<?php

namespace App\Helpers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestHelper
{
    public static function getAppointmentRules(FormRequest $request): array
    {
        $rules = [
            'client_id' => ['exists:clients,id', 'bail'],
            'dummy_client_id' => ['exists:dummy_clients,id', 'bail'],
            'specialist_id' => ['exists:specialists,id', 'bail'],
            'maintenance_id' => ['exists:maintenances,id', 'bail'],
            'date' => ['date_format:m.d.Y', 'bail'],
            'time_start' => ['date_format:H:i', 'bail'],
            'time_end' => ['date_format:H:i', 'bail', 'after:time_start'],
        ];

        if ($request->method() == 'POST') {
            $rules['client_id'][] = 'exclude_if:dummy_clients_id,!=,null';
            $rules['dummy_client_id'][] = 'exclude_if:client_id,!=,null';
            $rules['specialist_id'][] = 'required';
            $rules['maintenance_id'][] = 'required';
            $rules['date'][] = 'required';
            $rules['time_start'][] = 'required';
            $rules['time_end'][] = 'required';
        } elseif ($request->method() == 'PUT') {
            $rules['id'] = ['required', 'exists:appointments,id', 'bail'];
        }

        return $rules;
    }

    public static function getDummyClientRules(FormRequest $request): array
    {
        $rules = [
            'name' => ['string', 'bail'],
            'surname' => ['string', 'bail'],
            'phone_number' => ['string', 'bail', 'unique:dummy_clients,phone_number'],
            'discount' => ['numeric', 'between:0,1'],
            'avatar_id' => ['exists:images,id']
        ];
        if ($request->method() == 'POST') {
            $rules['name'][] = 'required';
            $rules['surname'][] = 'required';
            $rules['phone_number'][] = 'required';
            $rules['discount'][] = 'required';
            $rules['avatar_id'][] = 'required';
        } elseif ($request->method() == 'PUT') {
            $rules['id'] = ['required', 'exists:dummy_clients,id'];
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
            $rules['schedule'] = ['required', 'array'];
            $rules['schedule.type'] = [
                'required', Rule::in(WorkScheduleTypeHelper::getAllKeys()), 'bail'
            ];
            $rules['schedule.break_type'] = [
                'required', 'string', Rule::in(WorkScheduleTypeHelper::getBreakTypes()), 'bail'
            ];
            $rules['schedule.smart_schedule'] = ['required', 'boolean', 'bail'];
            $rules['schedule.confirmation'] = ['required', 'boolean', 'bail'];
            $rules['schedule.cancel_appointment'] = ['required', 'integer', 'bail'];
            $rules['schedule.limit_before'] = ['required', 'integer', 'bail'];
            $rules['schedule.limit_after'] = ['required', 'integer', 'bail'];
            $rules['schedule.workdays_count'] = ['integer', 'bail', 'required_if:type,==,sliding'];
            $rules['schedule.weekends_count'] = ['integer', 'bail', 'required_if:type,==,sliding'];
            $rules['schedule.start_from'] = ['date_format:d.m.Y', 'bail', 'required_if:type,==,sliding'];
            $rules['schedule.schedules'] = ['required', 'array', 'bail'];
            if ($request->schedule['type'] == 'sliding') {
                $rules = array_merge($rules, self::getSlidingScheduleRules());
            } else {
                $rules = array_merge($rules, self::getNotSlidingScheduleRules());
            }

            $rules['maintenance'] = [
                'finance_analytics' => ['required', 'boolean', 'bail'],
                'many_maintenances' => ['required', 'boolean', 'bail'],
                'specialist_id' => ['required' , 'exists:specialists,id', 'bail'],
                'maintenances' => ['required', 'array', 'bail'],
                'maintenances.*.title' => ['required', 'string', 'bail'],
                'maintenances.*.price' => ['required', 'integer', 'bail'],
                'maintenances.*.duration' => ['required', 'integer', 'bail']
            ];
        } else {
            $rules['id'] = ['required', 'exists:specialists,id'];
        }

        return $rules;
    }

    private static function getNotSlidingScheduleRules()
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

    private static function getSlidingScheduleRules()
    {
        return [
            // Sliding schedules
            'schedule.schedules.work.*.day' => ['integer', 'bail', 'required_if:type,==,sliding'],
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
