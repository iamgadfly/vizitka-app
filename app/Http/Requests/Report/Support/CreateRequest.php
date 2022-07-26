<?php

namespace App\Http\Requests\Report\Support;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * @throws SpecialistNotFoundException
     */
    protected function prepareForValidation()
    {
        $this->merge(['id' => AuthHelper::getSpecialistIdFromAuth()]);
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
            'id' => ['required', 'exists:specialists,id', 'bail'],
            'text' => ['required', 'string', 'bail'],
            'file' => ['file', 'nullable', 'bail'],
            'email' => ['required', 'email', 'bail']
        ];
    }
}
