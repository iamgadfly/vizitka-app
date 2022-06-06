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
            'maintenances.*.price' => ['required', 'array', 'bail'],
            'maintenances.*.price.label' => ['required', 'nullable', 'string', 'bail'],
            'maintenances.*.price.value' => ['required', 'nullable', 'integer', 'bail'],
            'maintenances.*.duration' => ['required', 'array', 'bail'],
            'maintenances.*.duration.label' => ['required', 'string', 'bail'],
            'maintenances.*.duration.value' => ['required', 'integer', 'bail']
        ];
    }

    public static function getWorkScheduleRules(FormRequest $request): array
    {
        $rules = [
            'specialist_id' => ['required', 'exists:specialists,id', 'bail'],
            'type' => ['required', 'array', 'bail'],
            'type.label' => ['required', 'string', 'bail'],
            'type.value' => ['required', Rule::in(WorkScheduleTypeHelper::getAllKeys()), 'bail'],
            'smart_schedule' => ['required', 'boolean', 'bail'],
            'confirmation' => ['required', 'boolean', 'bail'],
            'cancel_appointment' => ['required', 'array', 'bail'],
            'cancel_appointment.label' => ['required', 'string', 'bail'],
            'cancel_appointment.value' => ['required', 'integer', 'bail'],
            'limit_before' => ['required', 'array', 'bail'],
            'limit_before.label' => ['required', 'string', 'bail'],
            'limit_before.value' => ['required', 'integer', 'bail'],
            'limit_after' => ['required', 'array', 'bail'],
            'limit_after.label' => ['required', 'string', 'bail'],
            'limit_after.value' => ['required', 'integer', 'bail'],
        ];


        match ($request->type['value']) {
            'standard' => $rules = array_merge($rules, self::getStandardScheduleRules($request)),
            'flexible' => $rules = array_merge($rules, self::getFlexibleScheduleRules($request)),
            'sliding' => $rules = array_merge($rules, self::getSlidingScheduleRules($request)),
        };

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
            'user_id' => ['int', 'exists:users,id', 'bail'],
            'name' => ['string', 'max:255', 'bail'],
            'surname' => ['string', 'nullable',  'max:255', 'bail'],
            'avatar' => ['array', 'bail'],
            'avatar.id' => ['integer', 'nullable', 'exists:images,id', 'bail'],
            'avatar.url' => ['string', 'nullable', 'bail'],
            'activity_kind' => ['array', 'bail'],
            'activity_kind.label' => ['string', 'bail'],
            'activity_kind.value' => ['int', 'exists:activity_kinds,id', 'bail'],
            'title' => ['string', 'nullable', 'bail'],
            'about' => ['string', 'nullable', 'bail'],
            'address' => ['string', 'nullable', 'bail'],
            'placement' => ['string', 'nullable', 'bail'],
            'floor' => ['string', 'nullable', 'bail'],
            'instagram_account' => ['string', 'nullable', 'bail'],
            'youtube_account' => ['string', 'nullable', 'bail'],
            'vk_account' => ['string', 'nullable', 'bail'],
            'tiktok_account' => ['string', 'nullable', 'bail'],
            'background_image' => ['string', Rule::in(CardBackgroundHelper::$files), 'bail'],
        ];
        if ($request->method() == 'POST') {
            $rules['user_id'][] = 'required';
            $rules['name'][] = 'required';
            $rules['activity_kind'][] = 'required';
            $rules['activity_kind.label'][] = 'required';
            $rules['activity_kind.value'][] = 'required';
        } else {
            $rules['id'] = ['required', 'exists:specialists,id', 'bail'];
        }

        return $rules;
    }

    public static function getStandardScheduleRules(FormRequest $request): array
    {
        $rule = Rule::requiredIf(function () use ($request) {
            return $request->type['value'] == 'standard';
        });
        return [
            'standardSchedule' => [$rule, 'array', 'bail'],
            'standardSchedule.weekends' => [$rule, 'array', 'bail'],
            'standardSchedule.weekends.*.label' => [$rule, 'string', 'bail'],
            'standardSchedule.weekends.*.cut' => [$rule, 'string', 'bail'],
            'standardSchedule.weekends.*.value' => [$rule, 'string', 'bail', Rule::in(WeekdayHelper::getAll())],
            'standardSchedule.workTime' => [$rule, 'array', 'bail'],
            'standardSchedule.workTime.start' => [$rule, 'date_format:H:i', 'bail'],
            'standardSchedule.workTime.end' => [$rule, 'date_format:H:i', 'bail'],
            'standardSchedule.breaks' => [$rule, 'array', 'bail'],
            'standardSchedule.breaks.*.start' => [$rule, 'date_format:H:i', 'bail'],
            'standardSchedule.breaks.*.end' => [$rule, 'date_format:H:i', 'bail']
        ];
    }

    public static function getFlexibleScheduleRules(FormRequest $request): array
    {
        $rule = Rule::requiredIf(function () use ($request) {
            return $request->type['value'] == 'flexible';
        });
        return [
            'flexibleSchedule' => [$rule, 'array', 'bail'],
            'flexibleSchedule.data' => [$rule, 'array'],
            'flexibleSchedule.data.*.day' => [$rule, 'array', 'bail'],
            'flexibleSchedule.data.*.day.label' => [$rule, 'string', 'bail'],
            'flexibleSchedule.data.*.day.value' => [$rule, 'string', 'bail', Rule::in(WeekdayHelper::getAll())],
            'flexibleSchedule.data.*.workTime' => [$rule, 'array', 'bail'],
            'flexibleSchedule.data.*.workTime.start' => ['nullable', 'date_format:H:i', 'bail'],
            'flexibleSchedule.data.*.workTime.end' => ['nullable', 'date_format:H:i', 'bail'],
            'flexibleSchedule.data.*.breaks' => ['array', 'present', 'bail'],
            'flexibleSchedule.data.*.breaks.*.start' => ['date_format:H:i', 'present', 'bail'],
            'flexibleSchedule.data.*.breaks.*.end' => ['date_format:H:i', 'present', 'bail'],
            'flexibleSchedule.breakType' => [$rule, 'array', 'bail'],
            'flexibleSchedule.breakType.label' => [$rule, 'string', 'bail'],
            'flexibleSchedule.breakType.value' => [$rule, 'string', 'bail'],
            'flexibleSchedule.breaks' => ['present', $rule, 'array', 'bail'],
            'flexibleSchedule.breaks.*.start' => [$rule, 'date_format:H:i', 'nullable', 'bail'],
            'flexibleSchedule.breaks.*.end' => [$rule, 'date_format:H:i', 'nullable', 'bail'],
        ];
    }

    public static function getSlidingScheduleRules(FormRequest $request): array
    {
        $rule = Rule::requiredIf(function () use ($request) {
            return $request->type['value'] == 'sliding';
        });
        return [
            'slidingSchedule' => [$rule, 'array', 'bail'],
            'slidingSchedule.startFrom' => [$rule, 'array', 'bail'],
            'slidingSchedule.startFrom.label' => [$rule, 'date_format:d.m.Y', 'bail'],
            'slidingSchedule.startFrom.value' => [$rule, 'date_format:Y-m-d', 'bail'],
            'slidingSchedule.workdaysCount' => [$rule, 'integer', 'bail'],
            'slidingSchedule.weekdaysCount' => [$rule, 'integer', 'bail'],
            'slidingSchedule.data' => [$rule, 'array', 'bail'],
            'slidingSchedule.data.*.day' => [$rule, 'string', 'bail'],
            'slidingSchedule.data.*.workTime' => [$rule, 'array', 'bail'],
            'slidingSchedule.data.*.workTime.start' => [$rule, 'date_format:H:i', 'bail'],
            'slidingSchedule.data.*.workTime.end' => [$rule, 'date_format:H:i', 'bail'],
            'slidingSchedule.data.*.breaks' => [$rule, 'array', 'bail'],
            'slidingSchedule.data.*.breaks.*.start' => [$rule, 'date_format:H:i', 'bail'],
            'slidingSchedule.data.*.breaks.*.end' => [$rule, 'date_format:H:i', 'bail'],
            'slidingSchedule.breakType' => [$rule, 'array', 'bail'],
            'slidingSchedule.breakType.label' => [$rule, 'string', 'bail'],
            'slidingSchedule.breakType.value' => [$rule, 'string', 'bail'],
            'slidingSchedule.breaks' => [$rule, 'array', 'bail'],
            'slidingSchedule.breaks.*.start' => [$rule, 'date_format:H:i', 'bail'],
            'slidingSchedule.breaks.*.end' => [$rule, 'date_format:H:i', 'bail'],
        ];
    }
}
