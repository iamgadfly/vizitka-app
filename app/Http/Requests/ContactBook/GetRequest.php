<?php

namespace App\Http\Requests\ContactBook;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
{
    /**
     * @throws SpecialistNotFoundException
     */
    protected function prepareForValidation()
    {
        $this->merge(['specialist_id' =>  AuthHelper::getSpecialistIdFromAuth()]);
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
            'specialist_id' => ['required', 'exists:specialists,id']
        ];
    }
}
