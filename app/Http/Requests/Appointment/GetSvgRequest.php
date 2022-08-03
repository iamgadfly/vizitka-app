<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetSvgRequest
 *
 * @package App\Http\Requests\Appointment
 *
 * @property array $dates
 */
class GetSvgRequest extends FormRequest
{
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
    public function rules(): array
    {
        return [
            'dates' => 'required|array',
            'dates.*' => 'required|date_format:Y-m-d'
        ];
    }
}
