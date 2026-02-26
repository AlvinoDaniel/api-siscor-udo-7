<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentoExternoRequest extends FormRequest
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
            'contenido'             => "required",
            'asunto'                => "required",
            'remitente'             => "required|string",
            'tipo_remitente'        => "required|string",
            'documento_remitente'   => "required|string",
            'email_remitente'       => "required|email",
            'telefono_remitente'    => "nullable|string",
            'nro_doc'               => "nullable|string|unique:App\Models\DocumentoExterno,numero_oficio",
            'responder'             => "required|boolean",
            'fecha_emision'         => "required|date",
        ];
    }

    public function attributes()
    {
        return [
            'nro_doc'               => 'Número de Oficio',
            'documento_remitente'   => 'Documento de Identidad',
            'email_remitente'   => 'Correo Electrónico',
            'telefono_remitente'   => 'Teléfono contacto',
            'responder'   => 'Require resuesta',
            'fecha_emision'   => 'Fecha de emisión',
        ];
    }

    public function messages()
    {
        return [
            'nro_doc.unique'  => 'El Número de Oficio ya está registrado.',
        ];
    }
}
