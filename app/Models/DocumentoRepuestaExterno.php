<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoRepuestaExterno extends Model
{
    use HasFactory;
    protected $table = 'documentos_respuestas_externos';

    protected $fillable=[
        'id_documento',
        'id_documento_externo',
        'aprobado',
    ];

    public function respuesta() {
        return $this->belongsTo(Documento::class, 'id_documento');
    }

    public function documentoExterno() {
        return $this->belongsTo(DocumentoExterno::class, 'id_documento_externo');
    }
}
