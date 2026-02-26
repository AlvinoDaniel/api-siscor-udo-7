<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\EnviadosCollection;

class BandejaEnviadosCollection extends ResourceCollection
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
                'id'              => $item->id,
                'asunto'          => $item->asunto,
                'contenido'       => $item->contenido,
                'tipo_documento'  => $item->tipo_documento,
                'estatus'         => $item->estatus,
                'fecha_enviado'   => $item->fecha_enviado,
                'is_external'     => $item->is_external,
                'enviados'        => new EnviadosCollection($item->enviados),
                'anexos'          => count($item->anexos),
                'dpto_copias'     => new EnviadosCollection($item->dptoCopias),
            ];
        });
    }
}
