<?php

namespace App\Models\Biblioteca;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nome', 'cpf', 'email', 'telefone', 'tipo_usuario'])]
class Usuario extends Model
{
    protected $connection = 'biblioteca';

    protected $table = 'usuario';

    protected $primaryKey = 'id_usuario';

    public $timestamps = false;
}
