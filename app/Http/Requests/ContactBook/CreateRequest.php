<?php

namespace App\Http\Requests\ContactBook;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 *
 * @package App\Http\Requests\ContactBook
 *
 * @property integer $client_id
 */
class CreateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'client_id' => $this->route('id'),
            'type' => 'client'
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
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'type' => ['required', 'string', 'in:dummy,client']
        ];
    }
}
