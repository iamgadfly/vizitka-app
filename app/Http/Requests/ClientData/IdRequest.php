<?php

namespace App\Http\Requests\ClientData;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IdRequest
 *
 * @package App\Http\Requests\ClientData
 *
 * @property integer $id
 * @property string $type
 */
class IdRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
            'type' => $this->route('type')
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
            'id' => ['required', 'bail'],
            'type' => ['required', 'in:client,dummy']
        ];
    }
}
