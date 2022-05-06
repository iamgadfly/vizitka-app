<?php

namespace App\Http\Requests\Appointment;

use App\Helpers\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if ($this->method() == 'POST') {
            $this->merge(['specialist_id' => auth()->user()->specialist->id]);
        } elseif ($this->method() == 'PUT') {
            $this->merge(['id' => $this->route('id')]);
        }
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
        return RequestHelper::getAppointmentRules($this);
    }
}
