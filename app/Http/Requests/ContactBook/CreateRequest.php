<?php

namespace App\Http\Requests\ContactBook;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['client_id' => $this->route('id')]);
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
            'client_id' => ['required', 'exists:clients,id']
        ];
    }
}
