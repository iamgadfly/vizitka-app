<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
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
            'phone_number' => ['required', 'string', 'max:15', 'exists:users,phone_number' , 'bail'],
            'device_id' => ['required', 'string', 'bail'],
            'pin' => ['nullable', 'string', 'size:4', 'bail']
        ];
    }

    public function messages()
    {
        return [
            'phone_number.exists' => __('users.auth.validation.phone_number.exists')
        ];
    }
}
