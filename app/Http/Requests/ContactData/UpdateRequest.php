<?php

namespace App\Http\Requests\ContactData;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * @throws SpecialistNotFoundException
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'client_id' => $this->route('id'),
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth()
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
            'discount' => ['array', 'bail'],
            'discount.label' => ['string', 'nullable', 'bail'],
            'discount.value' => ['numeric', 'min:0', 'max:1', 'bail'],
            'notes' => ['string', 'nullable', 'bail'],
            'name' => ['string', 'nullable', 'bail'],
            'surname' => ['string', 'nullable', 'bail'],
            'phone_number' => ['string', 'max:15', 'bail'],
            'client_id' => ['required', 'exists:clients,id', 'bail'],
            'specialist_id' => ['required', 'exists:specialists,id', 'bail']
        ];
    }
}
