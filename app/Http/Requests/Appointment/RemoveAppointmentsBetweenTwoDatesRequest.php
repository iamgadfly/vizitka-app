<?php

namespace App\Http\Requests\Appointment;

use App\Helpers\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;

class RemoveAppointmentsBetweenTwoDatesRequest extends FormRequest
{
    /**
     * @throws \App\Exceptions\SpecialistNotFoundException
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth(),
            'start' => $this->route('start'),
            'end' => $this->route('end')
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
            'specialist_id' => ['required', 'exists:specialists,id', 'bail'],
            'start' => ['required', 'date_format:Y-m-d', 'bail'],
            'end' => ['required', 'date_format:Y-m-d', 'bail']
        ];
    }
}
