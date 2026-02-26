<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Documento;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BandejaRequest;
use App\Models\DocumentosDepartamento;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BandejaEnviadosCollection;
use App\Http\Resources\BandejaRecibidosCollection;
use App\Http\Resources\BandejaPorCorregirCollection;
use App\Http\Resources\BandejaExternosCollection;
use App\Http\Resources\BandejaExternoSalidaCollection;
use Illuminate\Support\Carbon;

class BandejaController extends AppBaseController
{
     /**
     * OBTENER DOCUMENTOS ENVIADOS AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enviados()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::with(['documentos' => function ($query) {
                $query->where('estatus', Documento::ESTATUS_ENVIADO);
                $query->orWhere('estatus', Documento::ESTATUS_ENVIADO_ALL);
            }])->find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' => new BandejaEnviadosCollection(
                        $departamento->documentos->filter(function ($item) {
                            return !$item->is_external;
                        })
                    ),
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     /**
     * OBTENER DOCUMENTOS BORRADORES AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function borradores()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::with(['documentos' => function ($query) {
                $query->where('estatus', Documento::ESTATUS_BORRADOR);
                $query->where('user_id', Auth::user()->id);
            }])->find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' =>new BandejaPorCorregirCollection($departamento->documentos)
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     /**
     * OBTENER DOCUMENTOS POR CORREGIR AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function corregir()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::with(['documentos' => function ($query) {
                $query->where('estatus', Documento::ESTATUS_POR_CORREGIR);
            }, 'documentos.esRespuesta', 'documentos.esRespuestaAsignado'])->find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' => new BandejaPorCorregirCollection($departamento->documentos)
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     /**
     * OBTENER DOCUMENTOS RECIBIDOS AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recibidos()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::with(['recibidos', 'asignados'])->find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' => new BandejaRecibidosCollection($departamento->recibidos->merge($departamento->asignados))
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     /**
     * OBTENER CANTIDAD DE DOCUMENTOS NO LEIDOS
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bandeja()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;

            $recibidos = DocumentosDepartamento::where('departamento_id', $departamento_user)
                ->where('leido', 0)
                ->get();

            $por_corregir = Documento::whereHas('temporal', function (Builder $query) {
                    $query->where('leido', 0);
                })->where('departamento_id', $departamento_user)->get();

            $message = 'bandeja';

            return $this->sendResponse(
                [
                    'recibidos' => count($recibidos),
                    'por_corregir' => count($por_corregir),
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

      /**
     * VERIFICAR SI EXISTE UN NUEVO DOCUMENTO RECIBIDO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function hasNewDocuments(Request $request)
    {
        $time = $request['time'] || Carbon::today()->toDateTimeLocalString();
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $recibidos = DocumentosDepartamento::where('departamento_id', $departamento_user)
            ->where('leido', 0)
            ->whereDate('created_at', '>', $time)
            ->get();

            $HAS_NEW = count($recibidos) > 0;
            $message = 'Nuevo documentos';
            return $this->sendResponse(
                [
                    'new' => $HAS_NEW
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

     /**
     * OBTENER DOCUMENTOS EXTERNOS REGISTRADO AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function externos()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::with(['documentos_externos', 'asignadosExternos'])->find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' => new BandejaExternosCollection($departamento->documentos_externos->merge($departamento->asignadosExternos))
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

     /**
     * OBTENER DOCUMENTOS RECIBIDOS AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function externosSalida()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' =>  $departamento->documentosExternosEnviados()
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     /**
     * OBTENER DOCUMENTOS RECIBIDOS AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function externosPorAprobar()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::with(['documentos' => function ($query) {
                $query->where('estatus', Documento::ESTATUS_POR_APROBAR);
            }, 'documentos.respuestaExterno.documentoExterno.remitente'])->find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' => $departamento->documentos
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
