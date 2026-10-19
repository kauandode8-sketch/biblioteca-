<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'livro_id',
    'nome',
    'email',
    'codigo',
    'status',
    'expira_em',
])]
class Reserva extends Model
{
    protected function casts(): array
    {
        return [
            'expira_em' => 'datetime',
        ];
    }

    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class);
    }
}
