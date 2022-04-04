<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use function auth;

class CreateClientRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['user_id' => auth()->id()]);
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
            'user_id' => 'required|int|exists:users,id',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'avatar' => 'image'
        ];
    }
}
