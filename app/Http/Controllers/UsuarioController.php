<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'email' => ['required', 'email'],
            'senha' => ['required', 'string'],
        ]);

        $usuario = Usuario::query()->where('email', Str::lower($dados['email']))->first();

        if ($usuario === null || $usuario->password === null || ! Hash::check($dados['senha'], $usuario->password)) {
            return response()->json(['message' => 'E-mail ou senha inválidos.'], 401);
        }

        return response()->json([
            'usuario' => $usuario->only(['id', 'nome', 'email']),
            'token' => $usuario->createToken('thunder-client')->plainTextToken,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user() instanceof Funcionario, 403, 'Somente funcionários podem listar usuários.');

        return response()->json(Usuario::query()->orderBy('nome')->paginate(20));
    }

    public function show(Request $request, Usuario $usuario): JsonResponse
    {
        $this->authorizeUsuario($request, $usuario);

        return response()->json(['usuario' => $usuario->only(['id', 'nome', 'email', 'cpf', 'data_nascimento', 'created_at'])]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:usuario,email'],
            'cpf' => ['required', 'digits:11', 'unique:usuario,cpf'],
            'data_nascimento' => ['required', 'date', 'before:today'],
            'senha' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $usuario = Usuario::query()->create([
            'nome' => Str::squish($dados['nome']),
            'email' => Str::lower($dados['email']),
            'cpf' => $dados['cpf'],
            'data_nascimento' => $dados['data_nascimento'],
            'password' => $dados['senha'],
        ]);

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'message' => 'Cadastro realizado com sucesso.',
                'usuario' => $usuario->only(['id', 'nome', 'email']),
                'token' => $usuario->createToken('thunder-client')->plainTextToken,
            ], 201);
        }

        return to_route('login')->with('status', 'Cadastro realizado. Entre com seu e-mail e senha.');
    }

    public function update(Request $request, Usuario $usuario): JsonResponse
    {
        $this->authorizeUsuario($request, $usuario);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('usuario', 'email')->ignore($usuario->getKey())],
            'cpf' => ['required', 'digits:11', Rule::unique('usuario', 'cpf')->ignore($usuario->getKey())],
            'data_nascimento' => ['required', 'date', 'before:today'],
            'senha' => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $dadosUsuario = [
            'nome' => Str::squish($dados['nome']),
            'email' => Str::lower($dados['email']),
            'cpf' => $dados['cpf'],
            'data_nascimento' => $dados['data_nascimento'],
        ];

        if (! empty($dados['senha'])) {
            $dadosUsuario['password'] = $dados['senha'];
        }

        DB::transaction(function () use ($usuario, $dadosUsuario): void {
            $emailAnterior = $usuario->email;

            if ($emailAnterior !== $dadosUsuario['email']) {
                $usuario->reservas()->update([
                    'email' => $dadosUsuario['email'],
                    'nome' => $dadosUsuario['nome'],
                ]);
            }

            $usuario->update($dadosUsuario);
        });

        return response()->json([
            'message' => 'Usuário atualizado com sucesso.',
            'usuario' => $usuario->fresh()->only(['id', 'nome', 'email', 'cpf', 'data_nascimento']),
        ]);
    }

    public function destroy(Request $request, Usuario $usuario): JsonResponse
    {
        $this->authorizeUsuario($request, $usuario);

        DB::transaction(function () use ($usuario): void {
            $usuario->tokens()->delete();
            $usuario->reservas()->delete();
            $usuario->delete();
        });

        return response()->json(['message' => 'Usuário excluído com sucesso.']);
    }

    private function authorizeUsuario(Request $request, Usuario $usuario): void
    {
        $ator = $request->user();

        abort_unless(
            $ator instanceof Funcionario || ($ator instanceof Usuario && $ator->is($usuario)),
            403,
            'Você não pode acessar este usuário.',
        );
    }
}
