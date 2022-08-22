<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->id()
        ]);
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
        return [
            'user_id' => ['required', 'integer', 'exists:users,id', 'bail'],
            'device_id' => ['required', 'string', 'exists:devices,device_id', 'bail'],
        ];
    }
}
