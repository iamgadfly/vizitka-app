<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SetPinRequest extends FormRequest
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
            'pin' => ['required', 'string', 'size:4', 'bail'],
            'phone_number' => ['required', 'string', 'bail'] ,
            'device_id' => ['required', 'string', 'exists:devices,device_id', 'bail']
        ];
    }
}
