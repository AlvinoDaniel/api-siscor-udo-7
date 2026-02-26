<?php

namespace App\Repositories;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Models\RemitenteExterno;
use App\Models\DocumentoExterno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DocumentoRequest;

class DocumentoExternoRepository {

    /**
     * @var Model
     */
    protected $model;

    /**
     * Base Repository Construct
     *
     * @param Model $model
     */
    public function __construct(DocumentoExterno $documento)
    {
        $this->model = $documento;
    }

    /**
     * Crear Documento
     */
    public function crearDocumento($request){

        $remitenteData = [
            "nombre_legal"              => $request->remitente,
            "documento_identidad"       => $request->documento_remitente,
            "correo"                    => $request->email_remitente,
            "telefono_contacto"         => $request->telefono_remitente,
        ];

        $data = [
            "contenido"                 => $request->contenido,
            "asunto"                    => $request->asunto,
            "importante"                => $request->important,
            "tipo"                      => $request->tipo_remitente,
            "departamento_receptor"     => Auth::user()->personal->departamento_id,
            "fecha_oficio"              => $request->fecha_emision,
            "responder"                 => $request->responder,
            "fecha_entrada"             => Carbon::now(),
            "estatus"                   => DocumentoExterno::ESTATUS_RECIBIDO,
            "numero_oficio"             => $request->has('nro_doc') ? $request->nro_doc : DocumentoExterno::generarCorrelativo()
        ];

        try {
            DB::beginTransaction();
                $remitente = RemitenteExterno::create($remitenteData);
                $data['id_remitente'] = $remitente->id;

                $documento = DocumentoExterno::create($data);
            DB::commit();
            return $documento;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
        }
    }
}
