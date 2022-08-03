<?php

namespace App\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetImageRequest
 *
 * @package App\Http\Requests\Image
 *
 * @property integer $id
 */
class GetImageRequest extends FormRequest
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
            'id' => ['required', 'integer', 'exists:images,id']
        ];
    }
}
