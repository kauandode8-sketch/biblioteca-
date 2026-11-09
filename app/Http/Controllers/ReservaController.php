<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Reserva;
use App\Models\Usuario;
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
        abort_unless($usuario instanceof Usuario, 403, 'Somente leitores podem reservar livros.');

        $reserva = DB::transaction(function () use ($livro, $usuario): Reserva {
            $livro = Livro::query()->lockForUpdate()->findOrFail($livro->id);
            $reservasAtivas = $livro->reservas()
                ->where('status', 'reservada')
                ->where('expira_em', '>', now());

            if ((clone $reservasAtivas)->where('email', $usuario->email)->exists()) {
                throw ValidationException::withMessages([
                    'reserva' => 'Você já tem uma reserva ativa para este livro.',
                ]);
            }

            $reservasAtivasCount = (clone $reservasAtivas)->count();
            $exemplaresDisponiveis = $livro->exemplares_totais - $livro->exemplares_emprestados - $reservasAtivasCount;

            if ($exemplaresDisponiveis < 1) {
                throw ValidationException::withMessages([
                    'reserva' => 'Não há exemplares disponíveis para reserva no momento.',
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

            return $reserva;
        });

        return to_route('livros.show', $livro)
            ->with('reserva_codigo', $reserva->codigo)
            ->with('reserva_expira_em', $reserva->expira_em->format('d/m/Y H:i'));
    }

    public function salvar_reserva(Request $request)
    {
        $request->validate([
            'livro_id' => 'required|integer|exists:livros,id',
            'nome' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'expira_em' => 'required|date',
        ]);

        try {
            $reserva = new Reserva;
            $reserva->livro_id = $request->livro_id;
            $reserva->nome = $request->nome;
            $reserva->email = $request->email;
            $reserva->codigo = $request->codigo;
            $reserva->status = $request->status;
            $reserva->expira_em = $request->expira_em;

            $reserva->save();

            return response()->json(['message' => 'Reserva cadastrada com sucesso!', 'erro' => 'n'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro ao cadastrar reserva!', 'erro' => 's', 'msg_erro' => $th->getMessage()], 200);
        }
    }

    public function listar_reserva(Request $request)
    {
        $reserva = Reserva::all();

        return response()->json(['reservas' => $reserva], 200);
    }

    public function alterar_reserva(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:reservas,id',
            'livro_id' => 'required|integer|exists:livros,id',
            'nome' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'expira_em' => 'required|date',
        ]);

        try {
            $reserva = Reserva::find($request->id);

            $reserva->livro_id = $request->livro_id;
            $reserva->nome = $request->nome;
            $reserva->email = $request->email;
            $reserva->codigo = $request->codigo;
            $reserva->status = $request->status;
            $reserva->expira_em = $request->expira_em;

            $reserva->save();

            return response()->json(['message' => 'Reserva alterada com sucesso!', 'erro' => 'n'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro ao alterar reserva!', 'erro' => 's', 'msg_erro' => $th->getMessage()], 200);
        }
    }

    public function deletar_reserva(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:reservas,id',
        ]);

        try {
            $reserva = Reserva::find($request->id);
            $reserva->delete();

            return response()->json(['message' => 'Reserva deletada com sucesso!', 'erro' => 'n'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro ao deletar reserva!', 'erro' => 's', 'msg_erro' => $th->getMessage()], 200);
        }
    }
}
