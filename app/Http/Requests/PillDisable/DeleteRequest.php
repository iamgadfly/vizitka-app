<?php

namespace App\Http\Requests\PillDisable;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'time' => $this->route('time'),
            'date' => $this->route('date')
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
            'time' => ['required', 'date_format:H:i', 'bail'],
            'date' => ['required', 'date_format:Y-m-d', 'bail']
        ];
    }
}
