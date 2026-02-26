<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BandejaExternosCollection extends ResourceCollection
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
            return [
                'id'                    => $item->id,
                'asunto'                => $item->asunto,
                'contenido'             => $item->contenido,
                'estatus'               => $item->estatus,
                'fecha_entrada'         => $item->fecha_entrada,
                'fecha_oficio'          => $item->fecha_oficio,
                'remitente'             => $item->remitente->nombre_legal,
                'doc_remitente'         => $item->remitente->documento_identidad,
                'respuesta'             => $item->respuesta,
                'requiere_respuesta'    => $item->responder,
                'numero_oficio'         => $item->numero_oficio,
                'importante'            => $item->importante,
                'tipo'                  => $item->tipo,
            ];
        });
    }
}
