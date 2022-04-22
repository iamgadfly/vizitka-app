<?php

namespace App\Http\Requests;

use App\Rules\Maintenance;
use Illuminate\Foundation\Http\FormRequest;

class MaintenanceRequest extends FormRequest
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
            'finance_analytics' => ['required', 'boolean', 'bail'],
            'many_maintenances' => ['required', 'boolean', 'bail'],
            'specialist_id' => ['required' , 'exists:specialists,id', 'bail'],
            'maintenances' => ['required', 'array', new Maintenance, 'bail']
        ];
    }
}
