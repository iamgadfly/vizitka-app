<?php

namespace App\Http\Requests\Share;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetByHashRequest
 *
 * @package App\Http\Requests\Share
 * @property string $hash
 */
class GetByHashRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['hash' => $this->route('hash')]);
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
            'hash' => ['required', 'exists:shares,hash']
        ];
    }
}
