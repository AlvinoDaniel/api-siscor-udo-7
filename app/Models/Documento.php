<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Departamento;
use App\Models\DocumentosDepartamento;
use App\Models\DocumentosTemporal;
use App\Models\Anexo;
use Illuminate\Support\Facades\Auth;

class Documento extends Model
{
    use HasFactory;

    const NAME = 'Documento';
    const ESTATUS_ENVIADO = 'enviado';
    const ESTATUS_ENVIADO_ALL = 'enviado_all';
    const ESTATUS_BORRADOR = 'borrador';
    const ESTATUS_POR_CORREGIR = 'por_corregir';
    const ESTATUS_POR_APROBAR = 'por_aprobar';
    const TIPO_CIRCULAR = 'circular';
    const TIPO_OFICIO = 'oficio';
    const TIPO_RESPUESTA_INTERNO = 'in';
    const TIPO_RESPUESTA_EXTERNO = 'ex';
    const ESTADO_ASIGNADO = 'ASIGNADO';
    const ESTADO_EN_PROCESO = 'EN PROCESO';
    const ESTADO_PROCESDO = 'PROCESADO';

    protected $fillable=[
        'asunto',
        'nro_documento',
        'contenido',
        'tipo_documento',
        'estatus',
        'estado',
        'fecha_enviado',
        'departamento_id',
        'copias',
        'user_id',
    ];

    protected $with = ['propietario', 'anexos', 'destinatario', 'respuesta', 'asignadoA'];

    protected $appends = ['is_external', 'es_asignado', 'is_copy'];

    public function propietario()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function anexos()
    {
        return $this->hasMany(Anexo::class);
    }

    public function destino()
    {
        return $this->hasOne(documentosDepartamento::class);
    }

    public function enviados()
    {
        return $this->belongsToMany(Departamento::class, 'documentos_departamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps()->wherePivot('copia', false);
    }

    public function destinatario(){
        return $this->enviados();
    }

    public function dptoCopias()
    {
        return $this->belongsToMany(Departamento::class, 'documentos_departamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps()->wherePivot('copia', true);
    }

    public function temporal()
    {
        return $this->hasOne(documentosTemporal::class);

    }

    public function esRespuesta()
    {
        return $this->hasOne(DocumentoRespuesta::class, 'documento_respuesta');
    }

    public function esRespuestaAsignado()
    {
        return $this->hasOne(DocumentoAsignado::class, 'id_documento_respuesta');
    }

    public function asignadoA()
    {
        return $this->morphOne(DocumentoAsignado::class, 'documentos_asignados', 'documento_type', 'documento_id')
        ->select('documentos_asignados.*', 'departamentos.nombre', 'departamentos.siglas', 'departamentos.id_departamento_superior')
        ->join('departamentos', 'departamentos.id', 'documentos_asignados.departamento_id');
    }

    public function getEsAsignadoAttribute(){
        return $this->asignadoA()->exists();
    }

    public function respuesta()
    {
        return $this->hasOne(DocumentoRespuesta::class, 'id_documento');
    }

    public function respuestaAsignado()
    {
        return $this->hasOne(DocumentoAsignado::class, 'documento_id');
    }

    public function respuestaExterno()
    {
        return $this->hasOne(DocumentoRepuestaExterno::class, 'id_documento');
    }

    public function getIsExternalAttribute(){
        return !is_null($this->respuestaExterno);
    }

    public function getIsCopyAttribute(){
       return $this->dptoCopias()->where('departamento_id', Auth::user()->personal->departamento_id)->get()->count() > 0;
    }


}
