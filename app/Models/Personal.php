<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;
use App\Models\User;
use App\Models\Nivel;
use Illuminate\Support\Facades\Storage;


class Personal extends Model
{
    use HasFactory;

    const NAME = 'Personal';

    protected $table = 'personal';

    protected $fillable=[
        'nombres_apellidos',
        'cedula_identidad',
        'cargo',
        'correo',
        'jefe',
        'descripcion_cargo',
        'cod_nucleo',
        'firma',
        'departamento_id',
        'nivel_id',
    ];

    protected $appends = ['firma_base_url'];
    protected $with = ['nivel'];

    public function usuario()
    {
        return $this->hasOne(User::class);
    }

    public function nivel()
    {
        return $this->hasOne(Nivel::class, 'id', 'nivel_id');
    }

    public function departamento() {
        return $this->belongsTo(Departamento::class);
    }

    public function getFirmaBaseUrlAttribute() {
        $existFile = $this->firma !== null ? Storage::disk('firmas')->exists($this->firma) : null;
        if($existFile){
            $image = Storage::disk('firmas')->get($this->firma);
            $mimeType = Storage::disk('firmas')->mimeType($this->firma);
            $imageConverted = base64_encode($image);
            $imageToBase64 = "data:{$mimeType};base64,{$imageConverted}";

            return $imageToBase64;
        }

       return null;
    }


}
