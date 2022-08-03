<?php

namespace App\Http\Requests\Misc;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetWeekDatesRequest
 *
 * @package App\Http\Requests\Misc
 *
 * @property DateTime $date
 */
class GetWeekDatesRequest extends FormRequest
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
