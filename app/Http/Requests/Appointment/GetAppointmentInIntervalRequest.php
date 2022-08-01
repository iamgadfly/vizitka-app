<?php

namespace App\Http\Requests\Appointment;

use App\Helpers\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;

class GetAppointmentInIntervalRequest extends FormRequest
{
    /**
     * @throws \App\Exceptions\SpecialistNotFoundException
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth(),
            'date' => $this->route('date'),
            'time_start' => $this->route('start'),
            'time_end' => $this->route('end')
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
            'date' => ['required', 'date_format:Y-m-d', 'bail'],
            'time_start' => ['required', 'date_format:H:i', 'bail'],
            'time_end' => ['required', 'date_format:H:i', 'bail'],
            'specialist_id' => ['required', 'exists:specialists,id', 'bail']
        ];
    }
}
