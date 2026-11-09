<?php

namespace App\Models\Biblioteca;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_categoria', 'titulo', 'autor', 'isbn', 'ano_publicacao', 'quantidade', 'disponivel'])]
class Livro extends Model
{
    protected $connection = 'biblioteca';

    protected $table = 'livro';

    protected $primaryKey = 'id_livro';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'ano_publicacao' => 'integer',
            'quantidade' => 'integer',
            'disponivel' => 'integer',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }
}
