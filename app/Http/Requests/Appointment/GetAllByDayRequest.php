<?php

namespace App\Http\Requests\Appointment;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetAllByDayRequest
 *
 * @package App\Http\Requests\Appointment
 *
 * @property DateTime $date
 */
class GetAllByDayRequest extends FormRequest
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
    public function rules()
    {
        return [
            'date' => ['required', 'date_format:Y-m-d']
        ];
    }
}
