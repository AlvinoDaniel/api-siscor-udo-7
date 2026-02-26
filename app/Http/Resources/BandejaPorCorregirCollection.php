<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Http\Resources\EnviadosCollection;

class BandejaPorCorregirCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            $dptoDestino = array_map('intval', explode(',' , $item->temporal->departamentos_destino));
            $dptoCopias = array_map('intval', explode(',' , $item->temporal->departamentos_copias));
            return [
                'id'              => $item->id,
                'asunto'          => $item->asunto,
                'contenido'       => $item->contenido,
                'tipo_documento'  => $item->tipo_documento,
                'estatus'         => $item->estatus,
                'leido'           => $item->temporal->leido,
                'fecha_creado'    => $item->created_at,
                'anexos'          => count($item->anexos),
                'enviados'        => Departamento::whereIn('id', $dptoDestino)->get(),
                'dpto_copias'     => $item->temporal->tieneCopia === 1 ? Departamento::whereIn('id', $dptoCopias)->get() : [],
                'respuesta'       => $item->respuesta,
                'es_respuesta'    => $item->esRespuesta,
                'respuesta_asignado'       => $item->esRespuestaAsignado,
            ];
        });
    }
}
