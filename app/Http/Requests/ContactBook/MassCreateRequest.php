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
            'data' => ['required', 'array', 'bail'],
            'data.*.name' => ['required', 'string', 'bail'],
            'data.*.surname' => ["nullable",'string', 'bail'],
            'data.*.phone_number' => ['required', 'string', 'max:100']
        ];
    }
}
