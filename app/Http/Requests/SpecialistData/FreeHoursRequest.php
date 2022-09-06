<?php

namespace App\Http\Requests\SpecialistData;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class FreeHoursRequest
 *
 * @package App\Http\Requests\SpecialistData
 *
 * @property integer $id
 * @property DateTime $date
 * @property integer $sum
 * @property DateTime $hour
 */
class FreeHoursRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
            'date' => $this->route('date'),
            'sum' => $this->route('sum'),
            'hour' => $this->route('hour')
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
            'id' => ['required', 'exists:specialists,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'hour' => ['required', 'date_format:H:i'],
            'sum' => ['required', 'integer', 'gte:0']
        ];
    }
}
