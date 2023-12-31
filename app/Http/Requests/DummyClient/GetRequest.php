<?php

namespace App\Http\Requests\DummyClient;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetRequest
 *
 * @package App\Http\Requests\DummyClient
 *
 * @property integer $id
 */
class GetRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
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
            'id' => ['required', 'integer', 'exists:dummy_clients,id']
        ];
    }
}
