<?php

namespace App\Http\Requests\Misc;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IsSpecialistExistsRequest
 *
 * @package App\Http\Requests\Misc
 *
 * @property string $phone_number
 */
class IsSpecialistExistsRequest extends FormRequest
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
            'phone_number' => ['required', 'string']
        ];
    }
}
