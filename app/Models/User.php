<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Personal;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'api';

    const NAME = 'Usuario';
    const ROLE_ADMIN        = 'administrador';
    const ROLE_JEFE         = 'jefe';
    const ROLE_SECRETARIA   = 'secretaria';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario',
        'email',
        'password',
        'status',
        'personal_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setPasswordAttribute($value){
        $this->attributes['password'] = \Hash::make($value);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function isJefe()
    {
        return $this->hasRole('jefe');
    }

    public function GET_JEFE() {
        $jefe = User::whereHas('personal' , function (Builder $query) use($personal) {
            $query->where('departamento_id', $this->id);
          })
          ->whereHas('roles', function (Builder $query) {
            $query->where('name', 'jefe');
          })
          ->first();

        return $jefe;
    }

}
