<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class NivelRequest extends FormRequest
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
            'descripcion'                => [
                "required",
                Rule::unique('nivel')->ignore($this->route('id'))
            ],
            'abreviatura'                => [
                "required",
                Rule::unique('nivel')->ignore($this->route('id'))
            ],
        ];
    }
}
