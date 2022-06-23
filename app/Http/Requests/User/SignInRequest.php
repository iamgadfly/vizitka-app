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
            'type' => 'required|string|in:client,specialist',
            'phone_number' => 'required|string|max:15|exists:users,phone_number|bail',
            'pin' => 'required_if:type,specialist|string|size:4|bail',
        ];
    }

    public function messages()
    {
        return [
            'type.in' => __('users.auth.validation.type.in'),
            'phone_number.exists' => __('users.auth.validation.phone_number.exists')
        ];
    }
}
