<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemitenteExterno extends Model
{
    use HasFactory;
    protected $table = 'remitentes_externos';

    protected $fillable=[
        'nombre_legal',
        'documento_identidad',
        'correo',
        'telefono_contacto',
    ];

    public function documentos(){
        return $this->hasMany(DocumentoExterno::class, 'id_remitente');
    }

}
