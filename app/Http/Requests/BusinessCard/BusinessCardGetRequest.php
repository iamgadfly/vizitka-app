<?php

namespace App\Http\Requests\BusinessCard;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class BusinessCardGetRequest
 *
 * @package App\Http\Requests\BusinessCard
 *
 * @property integer $id
 */
class BusinessCardGetRequest extends FormRequest
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
            'id' => ['required', 'integer', 'exists:business_cards,id']
        ];
    }
}
