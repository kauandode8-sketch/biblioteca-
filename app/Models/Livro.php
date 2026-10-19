<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'slug',
    'titulo',
    'autor',
    'editora',
    'ano_publicacao',
    'paginas',
    'isbn',
    'sinopse',
    'categoria',
    'assuntos',
    'classificacao',
    'localizacao',
    'exemplares_totais',
    'exemplares_emprestados',
])]
class Livro extends Model
{
    protected function casts(): array
    {
        return [
            'assuntos' => 'array',
        ];
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
