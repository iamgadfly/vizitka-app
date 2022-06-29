<?php

namespace App\Http\Requests\Specialist;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Helpers\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use function auth;

class CreateSpecialistRequest extends FormRequest
{
    /**
     * @throws SpecialistNotFoundException
     */
    protected function prepareForValidation()
    {
        if ($this->method() == 'PUT') {
            $this->merge(['id' => AuthHelper::getSpecialistIdFromAuth()]);
        } else {
            $this->merge(['user_id' => auth()->id()]);
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
        return RequestHelper::getSpecialistCreateOrUpdateRules($this);
    }
}
