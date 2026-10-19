<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Contracts\View\View;

class LivroController extends Controller
{
    public function index(): View
    {
        $livros = Livro::query()
            ->orderBy('titulo')
            ->get();

        return view('welcome', [
            'livros' => $livros,
            'nomeUsuario' => session('nome_usuario', 'Leitor'),
        ]);
    }

    public function show(Livro $livro): View
    {
        $reservasAtivas = $livro->reservas()
            ->where('status', 'reservada')
            ->where('expira_em', '>', now())
            ->count();

        $exemplaresDisponiveis = max(
            $livro->exemplares_totais - $livro->exemplares_emprestados - $reservasAtivas,
            0,
        );

        return view('livros.show', [
            'livro' => $livro,
            'exemplaresDisponiveis' => $exemplaresDisponiveis,
        ]);
    }
}
