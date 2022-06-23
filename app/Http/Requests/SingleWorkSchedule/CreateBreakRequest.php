<?php

namespace App\Http\Requests\SingleWorkSchedule;

use Illuminate\Foundation\Http\FormRequest;

class CreateBreakRequest extends FormRequest
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
            'date' => ['required', 'date_format:Y-m-d', 'bail'],
            'start' => ['required', 'date_format:H:i', 'bail'],
            'end' => ['required', 'date_format:H:i', 'bail']
        ];
    }
}
