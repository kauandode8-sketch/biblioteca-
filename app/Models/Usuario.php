<?php

namespace App\Models;

use Database\Factories\UsuarioFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['nome', 'email', 'cpf', 'data_nascimento', 'password'])]
#[Hidden(['password', 'remember_token'])]
class Usuario extends Authenticatable
{
    /** @use HasFactory<UsuarioFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuario';

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'password' => 'hashed',
        ];
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'email', 'email');
    }
}