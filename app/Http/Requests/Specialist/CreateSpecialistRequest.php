<?php

namespace App\Http\Requests\Specialist;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Helpers\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use function auth;

/**
 * Class CreateSpecialistRequest
 *
 * @package App\Http\Requests\Specialist
 *
 * @property integer $user_id
 * @property string $name
 * @property string|null $surname
 * @property array $avatar
 * @property array $activity_kind
 * @property string|null $title
 * @property string|null $about
 * @property string|null $address
 * @property string|null $placement
 * @property string|null $floor
 * @property string|null $vk_account
 * @property string|null $youtube_account
 * @property string|null $tiktok_account
 * @property array $background__image
 */
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
