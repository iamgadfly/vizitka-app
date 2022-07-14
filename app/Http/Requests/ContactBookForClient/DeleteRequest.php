<?php

namespace App\Http\Requests\ContactBookForClient;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'specialist_id' => $this->route('id'),
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
            'specialist_id' => ['required', 'exists:specialists,id'],
            'type' => ['required', 'string', 'in:dummy,specialist']
        ];
    }
}
