<?php

namespace App\Helpers;

use App\Rules\DummyCardUniquePhoneNumber;
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
            'maintenances.*.price.label' => ['nullable', 'string', 'bail'],
            'maintenances.*.price.value' => ['nullable', 'integer', 'bail'],
            'maintenances.*.duration' => ['required', 'array', 'bail'],
            'maintenances.*.duration.label' => ['nullable', 'string', 'bail'],
            'maintenances.*.duration.value' => ['nullable', 'integer', 'bail']
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
            'time' => ['required', 'array', 'bail'],
            'time.start' => ['required', 'date_format:H:i', 'bail'],
            'time.end' => ['required', 'date_format:H:i', 'bail', 'after:time_start'],
            'date' => ['required', 'array', 'bail'],
            'date.value' => ['required', 'date_format:Y-m-d', 'bail'],
            'specialist_id' => ['required', 'exists:specialists,id', 'bail'],
            'services' => ['required', 'array', 'bail'],
            'services.*.id' => ['required', 'exists:maintenances,id', 'bail'],
            'services.*.title' => ['nullable', 'string', 'bail'],
            'services.*.duration' => ['array', 'bail'],
            'services.*.duration.label' => ['nullable', 'string', 'bail'],
            'services.*.duration.price' => ['nullable', 'integer', 'bail'],
            'services.*.price' => ['array', 'bail'],
            'services.*.price.label' => ['nullable', 'string', 'bail'],
            'services.*.price.value' => ['nullable', 'integer', 'bail'],
            'client' => ['required', 'array', 'bail'],
            'client.avatar' => ['nullable', 'string', 'bail'],
            'client.discount' => ['nullable', 'array', 'bail'],
            'client.discount.label' => ['nullable', 'string', 'bail'],
            'client.discount.value' => ['nullable', 'numeric', 'bail'],
            'client.full_name' => ['nullable', 'string', 'bail'],
            'client.name' => ['nullable', 'string', 'bail'],
            'client.surname' => ['nullable', 'string', 'bail'],
            'client.phone_number' => ['nullable', 'string', 'bail'],
            'client.type' => ['required', 'in:client,dummy', 'bail'],
        ];

        if ($request->type == 'client') {
            $rules['client.id'] = ['nullable', 'exists:clients,id', 'bail'];
        } else {
            $rules['client.id'] = ['nullable', 'exists:dummy_clients,id', 'bail'];
        }

        if ($request->method() == 'PUT') {
            $rules['order_number'] = ['required', 'exists:appointments,order_number', 'bail'];
        }

        return $rules;
    }

    public static function getDummyClientRules(FormRequest $request): array
    {
        $rules = [
            'name' => ['string', 'bail'],
            'surname' => ['string', 'nullable', 'bail'],
            'phone_number' => ['string', 'bail'],
            'discount' => ['array', 'bail'],
            'discount.label' => ['string', 'nullable', 'bail'],
            'discount.value' => ['numeric', 'min:0', 'max:1', 'bail'],
            'avatar_id' => ['exists:images,id', 'nullable', 'bail'],
            'notes' => ['string', 'nullable', 'bail']
        ];
        if ($request->method() == 'POST') {
            $rules['name'][] = 'required';
            $rules['phone_number'][] = 'required';
            $rules['specialist_id'] = ['required', 'exists:specialists,id', 'bail'];
        } elseif ($request->method() == 'PUT') {
            $rules['id'] = ['required', 'exists:dummy_clients,id', 'bail'];
        }
        return $rules;
    }

    public static function getDummyBusinessCardRules(FormRequest $request): array
    {
        $rules = [
            'client_id' => ['exists:clients,id', 'bail'],
            'name' => ['string', 'bail'],
            'surname' => ['string', 'nullable', 'bail'],
            'title' => ['string', 'nullable', 'bail'],
            'about' => ['string', 'nullable', 'bail'],
            'avatar_id' => ['exists:images,id', 'nullable', 'bail'],
            'phone_number' => ['string', 'bail', 'nullable']
        ];
        if ($request->method() == 'POST') {
            $rules['client_id'][] = 'required';
            $rules['name'][] = 'required';
            $rules['phone_number'][] = 'required';
        } elseif ($request->method() == 'PUT') {
            $rules['id'] = ['required', 'exists:dummy_business_cards,id'];
            if (!is_null($request->phone_number)) {
                $rules['phone_number'][] = new DummyCardUniquePhoneNumber($request->phone_number);
            }
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
            'avatar_id' => ['exists:images,id', 'nullable']
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
            'activity_kind.label' => ['string', 'nullable','bail'],
            'activity_kind.value' => ['int', 'exists:activity_kinds,id', 'bail'],
            'title' => ['string', 'nullable', 'bail'],
            'about' => ['string', 'nullable', 'bail'],
            'address' => ['string', 'nullable', 'bail'],
            'placement' => ['string', 'nullable', 'bail'],
            'floor' => ['string', 'nullable', 'bail'],
            'youtube_account' => ['string', 'nullable', 'bail'],
            'vk_account' => ['string', 'nullable', 'bail'],
            'tiktok_account' => ['string', 'nullable', 'bail'],
            'background_image' => ['array', 'bail'],
            'background_image.value' => ['string', Rule::in(CardBackgroundHelper::$files), 'bail'],
            'background_image.url' => ['string', 'nullable', 'bail'],
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
            'standardSchedule' => [$rule, 'array', 'nullable', 'bail'],
            'standardSchedule.weekends' => ['array', 'bail'],
            'standardSchedule.weekends.*.label' => ['string', 'bail'],
            'standardSchedule.weekends.*.cut' => ['string', 'bail'],
            'standardSchedule.weekends.*.value' => ['string', 'bail', Rule::in(WeekdayHelper::getAll())],
            'standardSchedule.workTime' => ['array', 'bail'],
            'standardSchedule.workTime.start' => ['date_format:H:i', 'bail'],
            'standardSchedule.workTime.end' => ['date_format:H:i', 'bail'],
            'standardSchedule.breaks' => ['array', 'present', 'bail'],
            'standardSchedule.breaks.*.start' => ['date_format:H:i', 'bail'],
            'standardSchedule.breaks.*.end' => ['date_format:H:i', 'bail']
        ];
    }

    public static function getFlexibleScheduleRules(FormRequest $request): array
    {
        $rule = Rule::requiredIf(function () use ($request) {
            return $request->type['value'] == 'flexible';
        });
        return [
            'flexibleSchedule' => [$rule, 'array', 'nullable', 'bail'],
            'flexibleSchedule.data' => [$rule, 'array'],
            'flexibleSchedule.data.*.day' => [$rule, 'array', 'bail'],
            'flexibleSchedule.data.*.day.label' => [$rule, 'string', 'bail'],
            'flexibleSchedule.data.*.day.value' => [$rule, 'string', 'bail', Rule::in(WeekdayHelper::getAll())],
            'flexibleSchedule.data.*.workTime' => [$rule, 'array', 'bail'],
            'flexibleSchedule.data.*.workTime.start' => ['nullable', 'date_format:H:i', 'bail'],
            'flexibleSchedule.data.*.workTime.end' => ['nullable', 'date_format:H:i', 'bail'],
            'flexibleSchedule.data.*.breaks' => ['array', 'present', 'nullable', 'bail'],
            'flexibleSchedule.data.*.breaks.*.start' => ['date_format:H:i', 'present', 'bail'],
            'flexibleSchedule.data.*.breaks.*.end' => ['date_format:H:i', 'present', 'bail'],
            'flexibleSchedule.breakType' => [$rule, 'array', 'bail'],
            'flexibleSchedule.breakType.label' => ['string', 'bail'],
            'flexibleSchedule.breakType.value' => ['string', 'bail'],
            'flexibleSchedule.breaks' => ['present', 'array', 'nullable', 'bail'],
            'flexibleSchedule.breaks.*.start' => ['date_format:H:i', 'nullable', 'bail'],
            'flexibleSchedule.breaks.*.end' => ['date_format:H:i', 'nullable', 'bail'],
        ];
    }

    public static function getSlidingScheduleRules(FormRequest $request): array
    {
        $rule = Rule::requiredIf(function () use ($request) {
            return $request->type['value'] == 'sliding';
        });
        return [
            'slidingSchedule' => [$rule, 'array', 'nullable', 'bail'],
            'slidingSchedule.startFrom' => [$rule, 'array', 'bail'],
            'slidingSchedule.startFrom.value' => [$rule, 'date_format:Y-m-d', 'bail'],
            'slidingSchedule.workdaysCount' => [$rule, 'integer', 'bail'],
            'slidingSchedule.weekdaysCount' => [$rule, 'integer', 'bail'],
            'slidingSchedule.data' => [$rule, 'array', 'bail'],
            'slidingSchedule.data.*.day' => [$rule, 'string', 'bail'],
            'slidingSchedule.data.*.workTime' => [$rule, 'array', 'bail'],
            'slidingSchedule.data.*.workTime.start' => ['date_format:H:i', 'nullable', 'bail'],
            'slidingSchedule.data.*.workTime.end' => ['date_format:H:i', 'nullable', 'bail'],
            'slidingSchedule.data.*.breaks' => ['array', 'nullable', 'bail'],
            'slidingSchedule.data.*.breaks.*.start' => ['date_format:H:i', 'nullable', 'bail'],
            'slidingSchedule.data.*.breaks.*.end' => ['date_format:H:i', 'nullable', 'bail'],
            'slidingSchedule.breakType' => [$rule, 'array', 'bail'],
            'slidingSchedule.breakType.label' => [$rule, 'string', 'bail'],
            'slidingSchedule.breakType.value' => [$rule, 'string', 'bail'],
            'slidingSchedule.breaks' => ['array', 'bail'],
            'slidingSchedule.breaks.*.start' => ['date_format:H:i', 'bail'],
            'slidingSchedule.breaks.*.end' => ['date_format:H:i', 'bail'],
        ];
    }
}
