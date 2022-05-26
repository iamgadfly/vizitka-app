<?php

namespace App\Http\Requests\WorkSchedule;

use Illuminate\Foundation\Http\FormRequest;

class WorkScheduleRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['specialist_id' => auth()->user()->specialist->id]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // TODO: rework this
        return [
//            'smart_schedule' => ['required', 'boolean', 'bail'],
//            'confirmation' => ['required', 'boolean', 'bail'],
//            'cancel_appointment' => ['required', 'integer', 'bail'],
//            'new_appointment_not_before_than' => ['required', 'integer', 'bail'],
//            'new_appointment_not_after_than' => ['required', 'integer', 'bail'],
//            'weekends' => ['required', 'array', new Weekday, 'bail'],
//            'type_id' => ['required', 'integer', 'exists:work_schedule_types,id', 'bail'],
//            'specialist_id' => ['required', 'integer', 'exists:specialists,id', 'bail'],
//            'schedules' => ['required', 'array', new WorkSchedule, 'bail'],
        ];
    }
}
