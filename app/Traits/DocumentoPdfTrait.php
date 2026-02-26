<?php

namespace App\Traits;

use App\Models\Departamento;
use App\Models\Documento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

trait DocumentoPdfTrait {

   public function genareteDocumentBase64($documento){
      try {
          $hasCopias = count($documento->dptoCopias) > 0;
          $copiasNombres = [];
          if($hasCopias){
              foreach ($documento->dptoCopias as $value) {
                 array_push($copiasNombres, $value->nombre);
              }
          }
          if($documento->is_external){
              $documento->load(['respuestaExterno.documentoExterno.remitente']);
          }
          // return $this->sendResponse($documento, '');
          $pdf = \PDF::loadView('pdf.documento', [
              'dptoPropietario'   => $documento->propietario->nombre,
              'dptoSiglas'        => $documento->propietario->siglas,
              'fechaEnviado'      => Carbon::create($documento->fecha_enviado)->format('d \d\e F \d\e Y'),
              'dptoCopias'        => implode(', ',$copiasNombres),
              'hasCopias'         => $hasCopias ,
              'contenido'         => $documento->contenido,
              'nroDocumento'      => $documento->nro_documento,
              'isExternal'        => $documento->is_external,
              'remitente'         => $documento->is_external ? $documento->respuestaExterno->documentoExterno->remitente : null,
              'isCircular'        => $documento->tipo_documento === 'circular',
              'isOficio'          => $documento->tipo_documento === 'oficio',
              'nucleo'            => $documento?->propietario?->nucleo?->nombre ?? '',
              'nucleoDireccion'   => $documento?->propietario?->direccion ?? $documento?->propietario?->nucleo?->direccion,
              'propietarioJefe'   => $documento?->propietario?->jefe?->nombres_apellidos,
              'propietarioCargo'  => $documento?->propietario?->jefe?->descripcion_cargo,
              'baseUrlFirma'      => $documento?->propietario?->jefe?->baseUrlFirma,
              'destino'           => $documento->tipo_documento === 'circular'
                  ? $this->getNames($documento->enviados, $documento->estatus)
                  : $documento->enviados[0]->jefe ?? '',

          ]);
          return base64_encode($pdf->output());
      } catch (\Throwable $th) {
          return $this->sendError(
              $th->getMessage()
              // $th->getCode() > 0
              //     ? $th->getMessage()
              //     : 'Hubo un error al intentar Obtener el documento'
          );
      }
  }

   public function genareteDocumentExternoBase64($documento){
        try {
            $pdf = \PDF::loadView('pdf.documentoExterno', [
                'numero_oficio'     => $documento->numero_oficio,
                'fechaOficio'       => Carbon::create($documento->fecha_oficio)->locale('es')->format('d \d\e F \d\e Y'),
                'contenido'         => $documento->contenido,
                'remitente'         => $documento->remitente->nombre_legal,
                'documentoRemitente'  => $documento->remitente->documento_identidad,
                'destino'           => Auth::user()->personal->departamento->jefe,

            ]);
            return base64_encode($pdf->output());
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function genaretePreviewBase64($data){
        try {
            $dptoPropietario = Departamento::find($data->propietario);
            $hasCopias = isset($data->copias);
            $dateNow = new Carbon();
            // return $dptoPropietario;
          // return $this->sendResponse($documento, '');
            $pdf = \PDF::loadView('pdf.documentoPreview', [
              'dptoPropietario'   => $dptoPropietario->nombre,
              'dptoSiglas'        => $dptoPropietario->siglas,
              'fechaEnviado'      => Carbon::create($data->fecha_enviado)->format('d \d\e F \d\e Y'),
              'dptoCopias'        => $data->copias,
              'hasCopias'         => $hasCopias ,
              'contenido'         => $data->contenido,
              'isCircular'        => $data->tipo_data === 'circular',
              'isOficio'          => $data->tipo_documento === 'oficio',
              'nucleo'            => $dptoPropietario?->nucleo?->nombre ?? '',
              'nucleoDireccion'   => $dptoPropietario?->direccion ?? $dptoPropietario?->nucleo?->direccion,
              'propietarioJefe'   => $dptoPropietario?->jefe?->nombres_apellidos,
              'propietarioCargo'  => $dptoPropietario?->jefe?->descripcion_cargo,
              'baseUrlFirma'      => $dptoPropietario?->jefe?->baseUrlFirma,
              'destino_nombres_apellidos'      => $data->jefe_destino,
              'destino_descripcion_cargo'      => $data->jefe_cargo_destino,
              'year'              => $dateNow->year,
              'destino'           => $data->tipo_documento === 'circular'
                  ? $data->destinoCircular
                  : '',

          ]);
          return base64_encode($pdf->output());
      } catch (\Throwable $th) {
          return $this->sendError(
              $th->getMessage()
              // $th->getCode() > 0
              //     ? $th->getMessage()
              //     : 'Hubo un error al intentar Obtener el documento'
          );
      }
  }

    function getNames($data, $estatus){

        if($estatus === 'enviado_all'){
            return 'COMUNIDAD UNIVERSITARIA';
        }

        $nombres = [];
        foreach ($data as $value) {
            array_push($nombres, $value->nombre);
        }

        return implode(', ',$nombres);
    }
}
