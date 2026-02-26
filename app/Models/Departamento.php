<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Models\Documento;
use App\Models\Personal;
use App\Models\Carpeta;
use App\Models\Plantilla;
use App\Models\Grupo;
use App\Models\User;
use App\Models\Nucleo;

class Departamento extends Model
{
    use HasFactory;

    const NAME = 'Departamento';

    protected $fillable=[
        'nombre',
        'siglas',
        'codigo',
        'correo',
        'cod_nucleo',
        'direccion',
        'id_departamento_superior',
        'correlativo'
    ];

    protected $appends = [
        'can_assign'
    ];

    protected $with = ['jefe', 'nucleo'];

    public function dptoSuperior() {
        return $this->belongsTo(self::class, 'id_departamento_superior', 'codigo');
    }

    public function subDepartamentos() {
        return $this->hasMany(self::class, 'id_departamento_superior', 'codigo');
    }

    public function documentos() {
        return $this->hasMany(Documento::class, 'departamento_id');
    }

    public function documentos_externos() {
        return $this->hasMany(DocumentoExterno::class, 'departamento_receptor');
    }

    public function carpetas() {
        return $this->belongsTo(Carpeta::class);
    }

    public function plantillas() {
        return $this->belongsTo(Plantilla::class);
    }

    public function recibidos()
    {
        return $this->belongsToMany(Documento::class, 'documentos_departamentos')
        ->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps();
    }

    public function asignados()
    {
        return $this->belongsToMany(Documento::class, 'documentos_asignados')
            ->withPivot('leido', 'fecha_leido')
            ->wherePivot('documento_type', Documento::class);
    }

    public function asignadosExternos()
    {
        return $this->belongsToMany(Documento::class, 'documentos_asignados')
            ->withPivot('leido', 'fecha_leido')
            ->wherePivot('documento_type', DocumentoExterno::class);
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupos_departamentos');
    }

    public function personal() {
        return $this->hasMany(Personal::class);
    }

    public function jefe() {
        return $this->hasOne(Personal::class)->where('jefe', 1);
    }

    public function nucleo() {
        return $this->hasOne(Nucleo::class, 'codigo_concatenado', 'cod_nucleo');
    }

    public function incrementingCorrelativo(){
        return $this->correlativo + 1;
    }

    public function getCorrelativo(){
        $nro = str_pad($this->correlativo, 4, '0', STR_PAD_LEFT);
        $dateNow = new Carbon();
        return $nro.'-'.$dateNow->year;
    }

    public function getCanAssignAttribute(){
        return self::where('id_departamento_superior', $this->codigo)->get()->count() > 0;
    }

    public function documentosExternosEnviados(){
        return $this->documentos()
        ->whereHas('respuestaExterno', function (Builder $query) {
            $query->where('aprobado', 1);
        })->get();
    }
}
