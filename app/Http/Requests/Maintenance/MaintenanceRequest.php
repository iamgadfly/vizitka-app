<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class MaintenanceRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
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
            'id' => ['required', 'exists:maintenances,id', 'bail'],
            'maintenances' => ['required', 'array', 'bail'],
            'maintenances.*.id' => ['required', 'exists:maintenances,id'],
            'maintenances.*.title' => ['required', 'string', 'bail'],
            'maintenances.*.price' => ['required', 'array', 'bail'],
            'maintenances.*.price.label' => ['nullable', 'string', 'bail'],
            'maintenances.*.price.value' => ['nullable', 'integer', 'bail'],
            'maintenances.*.duration' => ['required', 'array', 'bail'],
            'maintenances.*.duration.label' => ['nullable', 'string', 'bail'],
            'maintenances.*.duration.value' => ['nullable', 'integer', 'bail']
        ];
    }
}
