<?php

namespace App\Http\Requests\SpecialistData;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IdRequest
 *
 * @package App\Http\Requests\SpecialistData
 *
 * @property integer $id
 */
class IdRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
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
            'id' => ['required', 'exists:specialists,id']
        ];
    }
}
