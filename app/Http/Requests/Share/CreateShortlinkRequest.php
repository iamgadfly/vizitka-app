<?php

namespace App\Http\Requests\Share;

use Illuminate\Foundation\Http\FormRequest;

class CreateShortlinkRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'sharable_type' => $this->route('type'),
            'sharable_id' => $this->route('id')
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
            'url' => ['required', 'url'],
            'sharable_type' => ['required', 'string', 'in:specialist,dummy', 'bail'],
            'sharable_id' => ['required', 'integer', 'bail']
        ];
    }
}
