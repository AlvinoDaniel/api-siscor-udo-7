<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class ApiPasswordReset extends Model
{
    use HasFactory;

    protected $table = 'api_password_reset';
    protected $fillable=[
        'user_id',
        'token_signature',
        'token_type',
        'expires_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }


}
