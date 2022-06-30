<?php

namespace App\Http\Requests\ContactBookForClient;

use App\Exceptions\ClientNotFoundException;
use App\Helpers\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
{
    /**
     * @throws ClientNotFoundException
     */
    protected function prepareForValidation()
    {
        $this->merge(['client_id' => AuthHelper::getClientIdFromAuth()]);
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
