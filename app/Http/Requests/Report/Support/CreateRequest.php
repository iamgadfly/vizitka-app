<?php

namespace App\Http\Requests\Report\Support;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * Class CreateRequest
 *
 * @package App\Http\Requests\Report\Support
 *
 * @property integer $id
 * @property string $text
 * @property string $email
 * @property UploadedFile $file
 */
class CreateRequest extends FormRequest
{
    /**
     * @throws SpecialistNotFoundException
     * @throws ClientNotFoundException
     */
    protected function prepareForValidation()
    {
        if ($this->routeIs('client.support.create')) {
            $id = AuthHelper::getClientIdFromAuth();
        } else {
            $id = AuthHelper::getSpecialistIdFromAuth();
        }
        $this->merge(['id' => $id]);
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
            'id' => ['required', 'integer', 'bail'],
            'text' => ['required', 'string', 'bail'],
            'file' => ['file', 'nullable', 'bail'],
            'email' => ['required', 'email', 'bail']
        ];
    }
}
