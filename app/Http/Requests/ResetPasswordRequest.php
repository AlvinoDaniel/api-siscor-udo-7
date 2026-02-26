<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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

    public function messages()
    {
        return [
            'email.exists' => 'Email no pertenece a ningun usuario registrado.',
            'identification.exists' => 'Cedula de Identidad no pertenece a ningun personal registrado.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'  => [
                "required",
                "email",
                "exists:users,email",
            ],
            'identification'  => [
                "required",
                "numeric",
                "exists:personal,cedula_identidad",
            ],
        ];
    }
}
