<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Reserva;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReservaController extends Controller
{
    public function store(Request $request, Livro $livro): RedirectResponse
    {
        $usuario = $request->user();
        $dados = [
            'nome' => $usuario->nome,
            'email' => Str::lower($usuario->email),
        ];

        $reserva = DB::transaction(function () use ($livro, $dados): Reserva {
            $livroBloqueado = Livro::query()
                ->lockForUpdate()
                ->findOrFail($livro->getKey());

            $reservaAtiva = $livroBloqueado->reservas()
                ->where('email', $dados['email'])
                ->where('status', 'reservada')
                ->where('expira_em', '>', now())
                ->exists();

            if ($reservaAtiva) {
                throw ValidationException::withMessages([
                    'reserva' => 'Você já tem uma reserva ativa para este livro.',
                ]);
            }

            $reservasAtivas = $livroBloqueado->reservas()
                ->where('status', 'reservada')
                ->where('expira_em', '>', now())
                ->count();

            $disponiveis = $livroBloqueado->exemplares_totais
                - $livroBloqueado->exemplares_emprestados
                - $reservasAtivas;

            if ($disponiveis < 1) {
                throw ValidationException::withMessages([
                    'reserva' => 'Não há exemplares disponíveis para reserva no momento.',
                ]);
            }

            $reserva = $livroBloqueado->reservas()->firstOrNew([
                'email' => $dados['email'],
            ]);

            $reserva->fill([
                'nome' => $dados['nome'],
                'codigo' => Str::upper(Str::random(8)),
                'status' => 'reservada',
                'expira_em' => now()->addHours(48),
            ]);
            $reserva->save();

            return $reserva;
        });

        return to_route('livros.show', $livro)
            ->with('reserva_codigo', $reserva->codigo)
            ->with('reserva_expira_em', $reserva->expira_em->format('d/m/Y \à\s H:i'));
    }
}
