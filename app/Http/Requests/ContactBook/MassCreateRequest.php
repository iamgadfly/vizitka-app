<?php

namespace App\Http\Requests\ContactBook;

use Illuminate\Foundation\Http\FormRequest;

class MassCreateRequest extends FormRequest
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
            'phone_numbers' => ['required', 'array'],
            'phone_numbers.*' => ['required', 'string', 'max:15']
        ];
    }
}
