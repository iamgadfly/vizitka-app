<?php

namespace App\Http\Requests\Client;

use App\Exceptions\ClientNotFoundException;
use App\Helpers\AuthHelper;
use App\Helpers\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use function auth;

class CreateClientRequest extends FormRequest
{
    /**
     * @throws ClientNotFoundException
     */
    protected function prepareForValidation()
    {
        if ($this->method() == 'POST'){
            $this->merge(['user_id' => auth()->id()]);
        } else {
            $this->merge(['id' => AuthHelper::getClientIdFromAuth()]);
        }
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
        return RequestHelper::getClientRules($this);
    }
}
