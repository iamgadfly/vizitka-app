<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IsUserExistsRequest
 *
 * @package App\Http\Requests\User
 *
 * @property string $phone_number
 * @property string $device_id
 */
class IsUserExistsRequest extends FormRequest
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
            'phone_number' => ['required', 'string', 'regex:/\+[0-9]{0,3}[0-9]{10}/', 'bail'],
            'device_id' => ['required', 'string', 'bail']
        ];
    }
}
