<?php

namespace App\Http\Requests\SingleWorkSchedule;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'break' => ['required', 'array', 'bail'],
            'break.time' => ['required', 'array', 'bail'],
            'break.date' => ['array', 'nullable', 'bail'],
            'break.date.label' => ['string', 'nullable', 'bail'],
            'break.date.value' => ['date_format:Y-m-d', 'nullable', 'bail'],
            'break.time.start' => ['date_format:H:i', 'nullable', 'bail'],
            'break.time.end' => ['date_format:H:i', 'nullable', 'bail'],
            'weekend' => ['required', 'array', 'bail'],
            'weekend.start' => ['date_format:Y-m-d', 'nullable', 'bail'],
            'weekend.end' => ['date_format:Y-m-d', 'nullable', 'bail'],
            'is_break' => ['required', 'boolean', 'bail'],
            'save' => ['boolean', 'nullable', 'bail', 'present']
        ];
    }
}
