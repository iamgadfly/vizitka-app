<?php

namespace App\Http\Requests\Blacklist;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'blacklisted_id' => $this->route('id'),
            'specialist_id' => auth()->user()->specialist->id
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
            'blacklisted_id' => ['required', 'exists:clients,id'],
            'specialist_id' => ['required', 'exists:specialists,id']
        ];
    }
}
