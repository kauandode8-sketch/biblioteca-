<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use App\Models\Livro;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReservaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $usuario = $request->user();
        $consulta = Reserva::query()->with('livro')->latest();

        if ($usuario instanceof Usuario) {
            $consulta->where('email', $usuario->email);
        } elseif (! ($usuario instanceof Funcionario)) {
            abort(403, 'Acesso negado.');
        }

        return response()->json($consulta->paginate(20));
    }

    public function show(Request $request, Reserva $reserva): JsonResponse
    {
        $this->authorizeReserva($request, $reserva);

        return response()->json(['reserva' => $reserva->load('livro')]);
    }

    public function store(Request $request): JsonResponse
    {
        $usuario = $request->user();
        abort_unless($usuario instanceof Usuario, 403, 'Somente usuários podem criar reservas.');

        $dados = $request->validate([
            'livro_id' => ['required', 'integer', 'exists:livros,id'],
        ]);

        $reserva = DB::transaction(function () use ($dados, $usuario): Reserva {
            $livro = Livro::query()->lockForUpdate()->findOrFail($dados['livro_id']);

            $reservasAtivas = $livro->reservas()
                ->where('status', 'reservada')
                ->where('expira_em', '>', now());

            if ((clone $reservasAtivas)->where('email', $usuario->email)->exists()) {
                throw ValidationException::withMessages([
                    'livro_id' => 'Você já tem uma reserva ativa para este livro.',
                ]);
            }

            $quantidadeReservas = (clone $reservasAtivas)->count();
            $disponiveis = $livro->exemplares_totais - $livro->exemplares_emprestados - $quantidadeReservas;

            if ($disponiveis < 1) {
                throw ValidationException::withMessages([
                    'livro_id' => 'Não há exemplares disponíveis para reserva no momento.',
                ]);
            }

            $reserva = $livro->reservas()->firstOrNew(['email' => $usuario->email]);
            $reserva->fill([
                'nome' => $usuario->nome,
                'codigo' => Str::upper(Str::random(8)),
                'status' => 'reservada',
                'expira_em' => now()->addHours(48),
            ]);
            $reserva->save();

            return $reserva->load('livro');
        });

        return response()->json([
            'message' => 'Reserva criada com sucesso.',
            'reserva' => $reserva,
        ], 201);
    }

    public function update(Request $request, Reserva $reserva): JsonResponse
    {
        abort_unless($request->user() instanceof Funcionario, 403, 'Somente funcionários podem alterar reservas.');

        $dados = $request->validate([
            'status' => ['required', Rule::in(['reservada', 'cancelada', 'retirada', 'expirada'])],
            'expira_em' => [Rule::requiredIf($request->input('status') === 'reservada'), 'nullable', 'date'],
        ]);

        $reserva->update([
            'status' => $dados['status'],
            'expira_em' => $dados['expira_em'] ?? $reserva->expira_em,
        ]);

        return response()->json([
            'message' => 'Reserva atualizada com sucesso.',
            'reserva' => $reserva->fresh()->load('livro'),
        ]);
    }

    public function destroy(Request $request, Reserva $reserva): JsonResponse
    {
        $this->authorizeReserva($request, $reserva);
        $reserva->delete();

        return response()->json(['message' => 'Reserva excluída com sucesso.']);
    }

    private function authorizeReserva(Request $request, Reserva $reserva): void
    {
        $usuario = $request->user();

        if ($usuario instanceof Funcionario) {
            return;
        }

        abort_unless($usuario instanceof Usuario && $reserva->email === $usuario->email, 403, 'Você não pode acessar esta reserva.');
    }
}
