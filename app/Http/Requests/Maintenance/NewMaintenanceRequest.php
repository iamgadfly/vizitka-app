<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class NewMaintenanceRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['specialist_id' => auth()->user()->specialist->id]);
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
            'specialist_id' => ['required', 'exists:specialists,id', 'bail'],
            'maintenances' => ['required', 'array', 'bail'],
            'maintenances.*.title' => ['required', 'string', 'bail'],
            'maintenances.*.price' => ['array', 'nullable', 'bail'],
            'maintenances.*.price.label' => ['nullable', 'string', 'bail'],
            'maintenances.*.price.value' => ['nullable', 'integer', 'bail'],
            'maintenances.*.duration' => ['required', 'array', 'bail'],
            'maintenances.*.duration.label' => ['string', 'nullable', 'bail'],
            'maintenances.*.duration.value' => ['required', 'integer', 'bail']
        ];
    }
}
