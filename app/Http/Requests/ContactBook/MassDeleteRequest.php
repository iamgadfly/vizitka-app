<?php

namespace App\Http\Requests\ContactBook;

use Illuminate\Foundation\Http\FormRequest;

class MassDeleteRequest extends FormRequest
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
            'client_ids' => ['present', 'array', 'bail'],
            'client_ids.*' => ['required', 'exists:contact_books,client_id', 'nullable', 'bail'],
            'dummy_client_ids' => ['present', 'array', 'bail'],
            'dummy_client_ids.*' => ['required', 'exists:contact_books,dummy_client_id', 'nullable', 'bail'],
        ];
    }
}
