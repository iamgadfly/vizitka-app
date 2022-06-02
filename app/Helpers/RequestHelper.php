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
            'flexible' => $rules = array_merge($rules, self::getFlexibleScheduleRules()),
            'sliding' => $rules = array_merge($rules, self::getSlidingScheduleRules()),
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

    public static function getFlexibleScheduleRules(): array
    {
        return [
            'flexibleSchedule' => ['required_if:type.value,==,flexible', 'array', 'bail'],
            'flexibleSchedule.data' => ['required_if:type.value,==,flexible', 'array'],
            'flexibleSchedule.data.*.day' => ['required_if:type.value,==,flexible', 'array', 'bail'],
            'flexibleSchedule.data.*.day.*.label' => ['required_if:type.value,==,flexible', 'string', 'bail'],
            'flexibleSchedule.data.*.day.*.value' => [
                'required_if:type.value,==,flexible', 'string', 'bail', Rule::in(WeekdayHelper::getAll())
            ],
            'flexibleSchedule.data.*.workTime' => ['required_if:type.value,==,flexible', 'array', 'bail'],
            'flexibleSchedule.data.*.workTime.start' => [
                'required_if:type.value,==,flexible', 'date_format:H:i', 'nullable', 'bail'
            ],
            'flexibleSchedule.data.*.workTime.end' => [
                'required_if:type.value,==,flexible', 'date_format:H:i', 'nullable', 'bail'
            ],
            'flexibleSchedule.data.*.breaks' => ['required_if:type.value,==,flexible', 'array', 'bail'],
            'flexibleSchedule.data.*.breaks.*.start' => [
                'required_if:type.value,==,flexible', 'date_format:H:i', 'bail']
            ,
            'flexibleSchedule.data.*.breaks.*.end' => ['required_if:type.value,==,flexible', 'date_format:H:i', 'bail'],
            'flexibleSchedule.breakType' => ['required_if:type.value,==,flexible', 'array', 'bail'],
            'flexibleSchedule.breakType.label' => ['required_if:type.value,==,flexible', 'string', 'bail'],
            'flexibleSchedule.breakType.value' => ['required_if:type.value,==,flexible', 'string', 'bail'],
            'flexibleSchedule.breaks' => ['required_if:type.value,==,flexible', 'array', 'bail', 'nullable'],
            'flexibleSchedule.breaks.*.start' => ['required_if:type.value,==,flexible', 'date_format:H:i', 'bail'],
            'flexibleSchedule.breaks.*.end' => ['required_if:type.value,==,flexible', 'date_format:H:i', 'bail'],
        ];
    }

    public static function getSlidingScheduleRules(): array
    {
        return [
            'slidingSchedule' => ['required_if:type.value,==,sliding', 'array', 'bail'],
            'startFrom' => ['required_if:type.value,==,sliding', 'array', 'bail'],
            'startFrom.label' => ['required_if:type.value,==,sliding', 'date_format:d-m-Y', 'bail'],
            'startFrom.value' => ['required_if:type.value,==,sliding', 'date_format:Y-m-d', 'bail'],
            'workdaysCount' => ['required_if:type.value,==,sliding', 'integer', 'bail'],
            'weekdaysCount' => ['required_if:type.value,==,sliding', 'integer', 'bail'],
            'data' => ['required_if:type.value,==,sliding', 'array', 'bail'],
            'data.*.day' => ['required_if:type.value,==,sliding', 'string', 'bail'],
            'data.*.workTime' => ['required_if:type.value,==,sliding', 'array', 'bail'],
            'data.*.workTime.start' => ['required_if:type.value,==,sliding', 'date_format:H:i', 'bail'],
            'data.*.workTime.end' => ['required_if:type.value,==,sliding', 'date_format:H:i', 'bail'],
            'data.*.breaks' => ['required_if:type.value,==,sliding', 'array', 'bail'],
            'data.*.breaks.*.start' => ['required_if:type.value,==,sliding', 'date_format:H:i', 'bail'],
            'data.*.breaks.*.end' => ['required_if:type.value,==,sliding', 'date_format:H:i', 'bail'],
            'breakType' => ['required_if:type.value,==,sliding', 'array', 'bail'],
            'breakType.label' => ['required_if:type.value,==,sliding', 'string', 'bail'],
            'breakType.value' => ['required_if:type.value,==,sliding', 'string', 'bail'],
            'breaks' => ['required_if:type.value,==,sliding', 'array', 'bail'],
            'breaks.*.start' => ['required_if:type.value,==,sliding', 'date_format:H:i', 'bail'],
            'breaks.*.end' => ['required_if:type.value,==,sliding', 'date_format:H:i', 'bail'],
        ];
    }
}
