<?php

namespace App\Repositories;

use Exception;
use Carbon\Carbon;
use App\Models\Anexo;
use App\Models\Documento;
use Illuminate\Support\Arr;
use App\Models\Departamento;
use App\Models\DocumentoAsignado;
use App\Models\DocumentoExterno;
use App\Models\DocumentoRepuestaExterno;
use App\Models\DocumentoRespuesta;
use App\Models\DocumentoRespuestaExterno;
use Illuminate\Support\Facades\DB;
use App\Models\DocumentosTemporal;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use App\Models\DocumentosDepartamento;
use Illuminate\Support\Facades\Storage;

class DocumentoRepository {



    /**
     * @var Model
     */
    protected $model;

    /**
     * Base Repository Construct
     *
     * @param Model $model
     */
    public function __construct(Documento $documento)
    {
        $this->model = $documento;
    }

    /**
     * Crear Documento
     */
    public function crearDocumento($data, $destino, $dataCopias){
        try {
            $data['user_id'] = Auth::user()->id;

            DB::beginTransaction();

            $dptoUser = Departamento::find($data['departamento_id']);
            $dptoUser->update([
                "correlativo" => Auth::user()->personal->departamento->incrementingCorrelativo()
            ]);
            $dptoUser->refresh();
            $data['nro_documento'] = $dptoUser->getCorrelativo();
            $documento = Documento::create($data);

            foreach ($destino as $dpto_destino) {
                DocumentosDepartamento::create([
                    'documento_id'        => $documento->id,
                    'departamento_id' => $dpto_destino,
                    'leido' => false,
                    'copia' => false,
                ]);
            }

            if($dataCopias['copia']){
                foreach ($dataCopias['departamentos'] as $dpto_copia) {
                    DocumentosDepartamento::create([
                        'documento_id'        => $documento->id,
                        'departamento_id' => $dpto_copia,
                        'leido' => false,
                        'copia' => true,
                    ]);
                }
            }

            DB::commit();
            return $documento;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('Hubo un error al intentar Enviar el documento.'.$th->getMessage());
            //
        }
    }
    /**
     * Crear Documento temporal
     */
    public function crearTemporalDocumento($data, $dataTemporal){

        try {
            DB::beginTransaction();
            $data['user_id'] = Auth::user()->id;
            $documento = Documento::create($data);

            $dataTemporal['documento_id'] = $documento->id;

            $temporal = DocumentosTemporal::create($dataTemporal);

            DB::commit();
            return $documento;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
            // throw new Exception($th->getMessage());
        }
    }
    public function attachAnexos($files, $id_documento) {
        try {
            DB::beginTransaction();
            foreach ($files as $anexo) {
                $fileName = $anexo->getClientOriginalName();
                $isExist = Storage::disk('anexos')->exists('/'.$id_documento.'/'.$fileName);
                if(!$isExist){
                    $path = Storage::disk('anexos')->putFileAs('/'.$id_documento, $anexo, $fileName);
                    $adjuntos[] = Anexo::create([
                        'documento_id' => $id_documento,
                        'nombre' => $fileName,
                        'urlAnexo' => $path
                    ]);
                }
            }
            DB::commit();
            $data = Anexo::where('documento_id', $id_documento)->get();
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
        }
    }

    public function deleteAnexo($id) {
        $anexo = Anexo::find($id);
        if(!$anexo) {
            throw new Exception('El anexo con id '.$id.' no existe.',422);
        }
        $documento_id = $anexo->documento_id;
        try {
            //Elimina el archivo fisico
            Storage::disk('anexos')->delete($anexo->urlAnexo);

             //eliminar el registro de la base de datos
            $anexo->delete();

        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }
    /**
     * Actualizar Documento temporal
     */
    public function updateTemporalDocumento($data, $dataTemporal, $id){
        $documento = Documento::find($id);
        if(!$documento){
            throw new Exception('El documento con id '.$id.' no existe.');
            return;
        }
        try {
            DB::beginTransaction();
            foreach ($data as $campo => $value) {
                if(!empty($value)){
                    $documento->update([$campo => $value]);
                }
            }

            if($data['estatus'] === Documento::ESTATUS_ENVIADO){

                $dptoUser = Departamento::find($data['departamento_id']);
                $dptoUser->update([
                    "correlativo" => Auth::user()->personal->departamento->incrementingCorrelativo()
                ]);
                $dptoUser->refresh();
                $id_nuevo = $dptoUser->getCorrelativo();

                if($dataTemporal['departamentos_destino'] === 'all') {
                    $propietario = Auth::user()->personal->departamento_id;
                    $departamentos_destino = DB::table('departamentos')->where('id','<>', $propietario)->pluck('id');
                } else {
                    $departamentos_destino = explode(',',trim($dataTemporal['departamentos_destino']));
                }
                $departamentos_copias = $dataTemporal['tieneCopia'] ? explode(',',trim($dataTemporal['departamentos_copias'])) : [];

                foreach ($departamentos_destino as $dpto_destino) {
                    DocumentosDepartamento::create([
                        'documento_id'        => $documento->id,
                        'departamento_id' => $dpto_destino,
                        'leido' => false,
                        'copia' => false,
                    ]);
                }

                if($dataTemporal['tieneCopia']){
                    foreach ($departamentos_copias as $dpto_copia) {
                        DocumentosDepartamento::create([
                            'documento_id'        => $documento->id,
                            'departamento_id' => $dpto_copia,
                            'leido' => false,
                            'copia' => true,
                        ]);
                    }
                }
                $temporal = DocumentosTemporal::where('documento_id', $id)->first();
                $temporal->delete();
                $documento->update(['nro_documento' => $id_nuevo]);
                $documento->update(['fecha_enviado' => Carbon::now()]);

            } else {
                foreach ($data as $campo => $value) {
                    if(!empty($value)){
                        $documento->update([$campo => $value]);
                    }
                }
                $dataTemporal['documento_id'] = $id;

                $temporal = DocumentosTemporal::where('documento_id', $id)->first();
                foreach ($dataTemporal as $campo => $value) {
                    if(!empty($value)){
                        $temporal->update([$campo => $value]);
                    }
                }
            }

            DB::commit();
            return $documento;
            } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('Hubo un error al intentar Guardar el documento.');
            // throw new Exception($th->getMessage());
            }
    }

    /**
   * Obtener un grupo de un Departamento
   * @param Integer $id
   */
   public function obtenerDocumento($id, $relaciones){
    try {

       $documento = Documento::with($relaciones)->find($id);
       if(!$documento) {
          throw new Exception('El documento con id '.$id.' no existe.',422);
       }
       $documento->propietario->load('nucleo');
       $url = $documento->propietario->jefe->firma;
       $existFile = $url !== null ? Storage::disk('firmas')->exists($url) : null;
       if($existFile){
            $image = Storage::disk('firmas')->get($url);
            $mimeType = Storage::disk('firmas')->mimeType($url);
            $imageConverted = base64_encode($image);
            $imageToBase64 = "data:{$mimeType};base64,{$imageConverted}";

            Arr::add($documento->propietario->jefe, 'baseUrlFirma', $imageToBase64);
       } else {
            Arr::add($documento->propietario->jefe, 'baseUrlFirma', null);
       }
       return $documento;
    } catch (\Throwable $th) {
      throw new Exception($th->getMessage(), $th->getCode());
    }
   }
    /**
     * Cambiar el estatus de Leido del documento
     * @param Integer $id
     */
    public function leidoDocumento($id){
        try {
            $documento = DocumentosDepartamento::where('documento_id',$id)->where('departamento_id', Auth::user()->personal->departamento_id)->first();
            if(!$documento) {
                throw new Exception('El documento con id '.$id.' no existe.',422);
            }
            if($documento->leido === 0){
                $documento->update(['leido' => true, 'fecha_leido' =>  Carbon::now()]);
            }
        } catch (\Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode());
        }
    }
    /**
     * Cambiar el estatus de Leido del documento asignado
     * @param Integer $id
     */
    public function leidoDocumentoAsignado($id){
        try {
            $asignado = DocumentoAsignado::where('documento_id',$id)->where('departamento_id', Auth::user()->personal->departamento_id)->first();
            $documento = Documento::find($id);
            if(!$asignado) {
                throw new Exception('El documento con id '.$id.' no existe.',422);
            }
            if($asignado->leido === 0){
                $asignado->update(['leido' => true, 'fecha_leido' =>  Carbon::now()]);
                $documento->fill([
                    "estado"    => Documento::ESTADO_EN_PROCESO
                ])->save();
            }
        } catch (\Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode());
        }
    }
    /**
     * Cambiar el estatus de Leido del documento
     * @param Integer $id
     */
    public function leidoDocumentoTemporal($id){
        $ES_JEFE = Auth::user()->isJefe();
        try {
            $documento = DocumentosTemporal::where('documento_id',$id)->first();
            if(!$documento) {
                throw new Exception('El documento con id '.$id.' no existe.',422);
            }
            if($documento->leido === 0 && $ES_JEFE){
                $documento->update(['leido' => 1]);
            }
        } catch (\Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode());
        }
    }

    public function eliminarDocumento($id) {
        $documento = Documento::find($id);
        if(!$documento) {
          throw new Exception('El documento con id '.$id.' no existe.',422);
        }

        try {
            // DB::beginTransaction();

            DocumentosTemporal::where('documento_id', $documento->id)->delete();
            $anexos = Anexo::where('documento_id', $documento->id)->delete();
            //Elimina los archivos fisico
            if($anexos > 0){
                Storage::disk('anexos')->deleteDirectory($documento->id);
            }
            $documento->delete();
            return $anexos;

            // DB::commit();
        } catch (\Throwable $th) {
            // DB::rollBack();
            throw new Exception($th->getMessage());
        }
    }

    public function obtenerDestinatario($document){
        $action = [
            "circular"  => $document->enviados,
            "oficio"    => $document->enviados[0],
        ];

        return $action[$document->tipo_documento] ?? [];
    }

    public function registrarRepuesta($data){
        try {
            DB::beginTransaction();
                if(!is_null($data['id_asignado'])){
                    $respuesta = DocumentoAsignado::find($data['id_asignado']);
                    if(!$respuesta) throw new Exception("No existe el documento asignado para dar respuesta.");

                    $respuesta->fill([
                        "aprobado"                  => $data['aprobado'],
                        "id_documento_respuesta"    => $data['doc'],
                    ])->save();

                    if($data['aprobado'] === '1'){
                        $documento = Documento::find($respuesta->documento_id);
                        $documento->fill([
                            "estado" => Documento::ESTADO_PROCESDO
                        ])->save();
                    }

                } else {
                    $dataRespuestaInt = [
                        "id_documento"          => $data['doc'],
                        "documento_respuesta"   => $data['doc_respuesta'],
                        "aprobado"              => $data['aprobado'],
                    ];
                    if($data['aprobado'] === '1'){
                        $dataRespuestaInt['fecha_respuesta'] = Carbon::now();
                    }

                    $respuesta = DocumentoRespuesta::updateOrCreate([
                        "id"  => $data['id_respuesta']
                    ],$dataRespuestaInt);
                }

            DB::commit();
            return $respuesta;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
            // throw new Exception($th->getMessage());
        }
    }

    public function registrarRepuestaExterno($data, $dataResponse, $id_documento = null){
        try {
            DB::beginTransaction();
                $data['user_id'] = Auth::user()->id;
                if($data['estatus'] === Documento::ESTATUS_ENVIADO){
                    $dptoUser = Departamento::find($data['departamento_id']);
                    $dptoUser->update([
                        "correlativo" => Auth::user()->personal->departamento->incrementingCorrelativo()
                    ]);
                    $dptoUser->refresh();
                    $data['nro_documento'] = $dptoUser->getCorrelativo();
                    $data['fecha_enviado'] = Carbon::now();
                }
                $documento = Documento::updateOrCreate([
                    "id"  => $id_documento
                ],$data);

                $dataRespuestaExt = [
                    "id_documento_externo"  => $dataResponse['doc_externo'],
                    "id_documento"          => $documento->id,
                    "aprobado"              => $dataResponse['aprobado'],
                ];

                if($dataResponse['aprobado'] === '1'){
                    $docExterno = DocumentoExterno::find($dataResponse['doc_externo']);
                    $docExterno->update([
                        "estatus"   => DocumentoExterno::ESTATUS_TRAMITADO
                    ]);
                }

                $respuesta = DocumentoRepuestaExterno::updateOrCreate([
                    "id"  => $dataResponse['id_respuesta']
                ],$dataRespuestaExt);

            DB::commit();
            return $documento;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
            // throw new Exception($th->getMessage());
        }
    }

    public function asignarDocumento($request){
        try {
            $documento  = Documento::find($request->documento);

            $asignado = $documento->asignadoA()->create([
                "departamento_id"   => $request->departamento,
                "fecha_asignado"   => Carbon::now(),
            ]);
            $documento->update([
                "estado"  => Documento::ESTADO_ASIGNADO
            ]);
            return $asignado;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function registrarRepuestaAsignado($data, $dataResponse, $id_documento = null){
        try {
            DB::beginTransaction();
                $data['user_id'] = Auth::user()->id;
                if($data['estatus'] === Documento::ESTATUS_ENVIADO){
                    $dptoUser = Departamento::find($data['departamento_id']);
                    $dptoUser->update([
                        "correlativo" => Auth::user()->personal->departamento->incrementingCorrelativo()
                    ]);
                    $dptoUser->refresh();
                    $data['nro_documento'] = $dptoUser->getCorrelativo();
                    $data['fecha_enviado'] = Carbon::now();
                }
                $documento = Documento::updateOrCreate([
                    "id"  => $id_documento
                ],$data);

                $dataRespuestaExt = [
                    "id_documento_externo"  => $dataResponse['doc_externo'],
                    "id_documento"          => $documento->id,
                    "aprobado"              => $dataResponse['aprobado'],
                ];

                if($dataResponse['aprobado'] === '1'){
                    $docExterno = DocumentoExterno::find($dataResponse['doc_externo']);
                    $docExterno->update([
                        "estatus"   => DocumentoExterno::ESTATUS_TRAMITADO
                    ]);
                }

                $respuesta = DocumentoRepuestaExterno::updateOrCreate([
                    "id"  => $dataResponse['id_respuesta']
                ],$dataRespuestaExt);

            DB::commit();
            return $documento;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
            // throw new Exception($th->getMessage());
        }
    }

}
