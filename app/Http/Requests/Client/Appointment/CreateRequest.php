<?php

namespace App\Http\Requests\Client\Appointment;

use App\Exceptions\ClientNotFoundException;
use App\Helpers\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * @throws ClientNotFoundException
     */
    protected function prepareForValidation()
    {
        if ($this->method() == 'PUT') {
            $this->merge([
                'order_number' => $this->route('orderNumber'),
                'client_id' => AuthHelper::getClientIdFromAuth()
            ]);
        } elseif ($this->method() == 'POST') {
            $this->merge([
                'specialist_id' => $this->route('id'),
                'client_id' => AuthHelper::getClientIdFromAuth()
            ]);
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
        $rules = [
            'specialist_id' => ['exists:specialists,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'maintenances' => ['required', 'array', 'bail'],
            'maintenance.*' => ['required', 'exists:maintenances,id', 'bail'],
            'date' => ['required', 'date_format:Y-m-d', 'bail'],
            'time_start' => ['required', 'date_format:H:i', 'bail']
        ];
        if ($this->method() == 'PUT') {
            $rules['order_number'] = ['required', 'exists:appointments,order_number', 'bail'];
        } else {
            $rules['specialist_id'][] = 'required';
        }

        return $rules;
    }
}
