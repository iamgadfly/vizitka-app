<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PinResetRequest extends FormRequest
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
            'phone_number' => 'required|max:15|exists:users|bail',
            'verification_code' => 'required|max:4|bail',
            'pin' => 'required|string|size:4|bail'
        ];
    }
}
