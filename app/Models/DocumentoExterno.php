<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class DocumentoExterno extends Model
{
    use HasFactory;
    protected $table = 'documentos_externos';

    protected $fillable=[
        'numero_oficio',
        'id_remitente',
        'contenido',
        'asunto',
        'estatus',
        'fecha_oficio',
        'departamento_receptor',
        'documento_respuesta',
        'responder',
        'fecha_entrada',
        'tipo',
        'importante',
    ];

    const ESTATUS_RECIBIDO = "En Proceso";
    const ESTATUS_TRAMITADO = "Tramitado";
    const SIGLAS_EXTERNO = "ESN";

    public $timestamps = false;

    protected $casts = [
        'importante' => 'boolean',
    ];

    protected $with = ['remitente', 'respuesta', ];

    public function remitente() {
        return $this->belongsTo(RemitenteExterno::class, 'id_remitente');
    }

    public function dptoReceptor() {
        return $this->belongsTo(Departamento::class, 'departamento_receptor');
    }

    public function respuesta(): HasOne {
        return $this->hasOne(DocumentoRepuestaExterno::class, 'id_documento_externo');
    }

    public function asignado(): MorphOne
    {
        return $this->morphOne(DocumentoAsignado::class, 'documento');
    }

    static function generarCorrelativo(){
        $countDocs = self::where('numero_oficio', 'like', self::SIGLAS_EXTERNO.'%')->get()->count();

        $correlativo = str_pad($countDocs + 1, 4, '0', STR_PAD_LEFT);
        $dateNow = new Carbon();

        return self::SIGLAS_EXTERNO.' '.$correlativo.'-'.$dateNow->year;
    }
}
