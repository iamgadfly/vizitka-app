<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IdRequest
 *
 * @package App\Http\Requests\Appointment
 *
 * @property string $order_number
 */
class IdRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['order_number' => $this->route('orderNumber')]);
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
            'order_number' => ['required', 'exists:appointments,order_number']
        ];
    }
}
