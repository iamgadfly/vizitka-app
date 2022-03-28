<?php

namespace App\Http\Requests;

use Axiom\Rules\LocationCoordinates;
use Illuminate\Foundation\Http\FormRequest;

class GeocodeRequest extends FormRequest
{
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
            'coordinates' => ['required', new LocationCoordinates]
        ];
    }

    public function messages()
    {
        return [
            'coordinates.required' => __('users.geocoder.validation.coordinates.required')
        ];
    }
}
