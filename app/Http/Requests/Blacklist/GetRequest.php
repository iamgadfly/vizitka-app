<?php

namespace App\Http\Requests\Blacklist;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetRequest
 *
 * @package App\Http\Request\Blacklist
 *
 * @property integer $specialist_id
 */
class GetRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['specialist_id' => auth()->user()->specialist->id]);
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
            'specialist_id' => ['required', 'integer', 'exists:specialists,id']
        ];
    }
}
