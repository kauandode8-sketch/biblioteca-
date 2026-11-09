<?php

namespace App\Models\Biblioteca;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nome', 'descricao'])]
class Categoria extends Model
{
    protected $connection = 'biblioteca';

    protected $table = 'categoria';

    protected $primaryKey = 'id_categoria';

    public $timestamps = false;

    public function livros(): HasMany
    {
        return $this->hasMany(Livro::class, 'id_categoria', 'id_categoria');
    }
}
