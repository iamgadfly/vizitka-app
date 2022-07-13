<?php

namespace App\Http\Requests\DummyBusinessCard;

use App\Exceptions\ClientNotFoundException;
use App\Helpers\AuthHelper;
use App\Helpers\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;

class DummyBusinessCardRequest extends FormRequest
{
    /**
     * @throws ClientNotFoundException
     */
    protected function prepareForValidation()
    {
        if ($this->method() == 'POST') {
            $this->merge(['client_id' => AuthHelper::getClientIdFromAuth()]);
        } else {
            $this->merge(['id' => $this->route('id')]);
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
        return RequestHelper::getDummyBusinessCardRules($this);
    }
}
