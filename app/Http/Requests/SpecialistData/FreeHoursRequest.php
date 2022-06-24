<?php

namespace App\Http\Requests\SpecialistData;

use Illuminate\Foundation\Http\FormRequest;

class FreeHoursRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
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
            'id' => ['required', 'exists:specialists,id'],
            'date' => ['required', 'date_format:Y-m-d']
        ];
    }
}
