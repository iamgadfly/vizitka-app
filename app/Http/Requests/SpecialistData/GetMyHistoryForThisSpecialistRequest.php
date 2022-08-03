<?php

namespace App\Http\Requests\SpecialistData;

use App\Exceptions\ClientNotFoundException;
use App\Helpers\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetMyHistoryForThisSpecialistRequest
 *
 * @package App\Http\Requests\SpecialistData
 *
 * @property integer $specialist_id
 * @property integer $client_id
 */
class GetMyHistoryForThisSpecialistRequest extends FormRequest
{
    /**
     * @throws ClientNotFoundException
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'specialist_id' => $this->route('id'),
            'client_id' => AuthHelper::getClientIdFromAuth()
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
            'specialist_id' => ['required', 'exists:specialists,id', 'bail'],
            'client_id' => ['required', 'exists:clients,id', 'bail']
        ];
    }
}
