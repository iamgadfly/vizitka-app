<?php

namespace App\Http\Requests\SingleWorkSchedule;

use Illuminate\Foundation\Http\FormRequest;

class CreateWorkdayRequest extends FormRequest
{
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
        return [
            'dates' => ['required', 'array', 'bail'],
            'dates.*' => ['required', 'date_format:Y-m-d', 'bail'],
            'workTime' => ['required', 'array', 'bail'],
            'workTime.start' => ['nullable', 'date_format:H:i', 'bail'],
            'workTime.end' => ['nullable', 'date_format:H:i', 'bail'],
            'breaks' => ['required', 'array', 'bail'],
            'breaks.*.start' => ['nullable', 'date_format:H:i', 'bail'],
            'breaks.*.end' => ['nullable', 'date_format:H:i', 'bail'],
        ];
    }
}
