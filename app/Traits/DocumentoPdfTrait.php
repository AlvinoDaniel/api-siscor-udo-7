<?php

namespace App\Traits;

use App\Models\Departamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

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
              'isExternal'        => $documento->is_external,
              'remitente'         => $documento->is_external ? $documento->respuestaExterno->documentoExterno->remitente : null,
              'isCircular'        => $documento->tipo_documento === 'circular',
              'isOficio'          => $documento->tipo_documento === 'oficio',
              'nucleo'            => $documento->propietario->nucleo->nombre ?? '',
              'nucleoDireccion'   => $documento->propietario->direccion ?? $documento->propietario->nucleo->direccion,
              'propietarioJefe'   => $documento->propietario->jefe->nombres_apellidos,
              'propietarioCargo'  => $documento->propietario->jefe->descripcion_cargo,
              'baseUrlFirma'      => $documento->propietario->jefe->baseUrlFirma,
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
}
