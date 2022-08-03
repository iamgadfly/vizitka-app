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
 */
class FreeHoursRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
            'date' => $this->route('date')
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
            'date' => ['required', 'date_format:Y-m-d']
        ];
    }
}
