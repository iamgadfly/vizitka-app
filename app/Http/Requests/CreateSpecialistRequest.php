<?php

namespace App\Http\Requests;

use App\Helpers\CardBackgroundHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSpecialistRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['user_id' => auth()->id()]);
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
            'user_id' => 'required|int|exists:users,id',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'avatar' => 'image',
            'activity_kind_id' => 'required|int|exists:activity_kinds,id',
            'title' => 'required|string',
            'about' => 'required|string',
            'address' => 'required|string',
            'placement' => 'string',
            'floor' => 'string',
            'instagram_account' => 'string',
            'youtube_account' => 'string',
            'vk_account' => 'string',
            'tiktok_account' => 'string',
            'background_image' => ['string', Rule::in(CardBackgroundHelper::$files)]
        ];
    }
}
