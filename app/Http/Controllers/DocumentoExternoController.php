<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DocumentoExternoRequest;
use App\Repositories\DocumentoExternoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DocumentoExterno;
use App\Traits\DocumentoPdfTrait;

class DocumentoExternoController extends AppBaseController
{
    private $repository;
    use DocumentoPdfTrait;

    public function __construct(DocumentoExternoRepository $documentoRepository)
    {
        $this->repository = $documentoRepository;
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentoExternoRequest $request)
    {
        try {
            $documento = $this->repository->crearDocumento($request);
            return $this->sendResponse(
                $documento,
                'Documento Externo enviado exitosamente'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $documento = DocumentoExterno::with(['respuesta.respuesta'])->find($id);
            $documento["pdf"] = $this->genareteDocumentExternoBase64($documento);
            return $this->sendResponse(
                $documento,
                'Documento Externo'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Obtener el documento'
            );
        }
    }

    public function genareteDocument($id){
        try {
            $documento = DocumentoExterno::find($id);
            $pdf = \PDF::loadView('pdf.documentoExterno', [
                'numero_oficio'     => $documento->numero_oficio,
                'fechaOficio'       => Carbon::create($documento->fecha_oficio)->locale('es')->format('d \d\e F \d\e Y'),
                'contenido'         => $documento->contenido,
                'remitente'         => $documento->remitente->nombre_legal,
                'documentoRemitente'  => $documento->remitente->documento_identidad,
                'destino'           => Auth::user()->personal->departamento->jefe,

            ]);
            return $pdf->download('Documento_Externo.pdf');
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getMessage()
                // $th->getCode() > 0
                //     ? $th->getMessage()
                //     : 'Hubo un error al intentar Obtener el documento'
            );
        }
    }

}
