<?php

namespace App\Http\Controllers\Api\Biblioteca;

use App\Http\Controllers\Controller;
use App\Models\Biblioteca\Usuario;
use App\Models\Funcionario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeFuncionario($request);

        return response()->json(Usuario::query()->orderBy('nome')->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeFuncionario($request);
        $dados = $this->validateUsuario($request);

        $dados['nome'] = Str::squish($dados['nome']);
        $dados['email'] = isset($dados['email']) ? Str::lower($dados['email']) : null;

        $usuario = Usuario::query()->create($dados);

        return response()->json([
            'message' => 'Usuário cadastrado com sucesso.',
            'data' => $usuario,
        ], 201);
    }

    public function show(Request $request, Usuario $usuario): JsonResponse
    {
        $this->authorizeFuncionario($request);

        return response()->json(['data' => $usuario]);
    }

    public function update(Request $request, Usuario $usuario): JsonResponse
    {
        $this->authorizeFuncionario($request);
        $dados = $this->validateUsuario($request, $usuario);

        if (array_key_exists('nome', $dados)) {
            $dados['nome'] = Str::squish($dados['nome']);
        }

        if (array_key_exists('email', $dados) && $dados['email'] !== null) {
            $dados['email'] = Str::lower($dados['email']);
        }

        $usuario->update($dados);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso.',
            'data' => $usuario->refresh(),
        ]);
    }

    public function destroy(Request $request, Usuario $usuario): JsonResponse
    {
        $this->authorizeFuncionario($request);
        $usuario->delete();

        return response()->json(['message' => 'Usuário excluído com sucesso.']);
    }

    /** @return array<string, mixed> */
    private function validateUsuario(Request $request, ?Usuario $usuario = null): array
    {
        $emailRule = Rule::unique('biblioteca.usuario', 'email');
        $cpfRule = Rule::unique('biblioteca.usuario', 'cpf');

        if ($usuario !== null) {
            $emailRule->ignore($usuario->getKey(), 'id_usuario');
            $cpfRule->ignore($usuario->getKey(), 'id_usuario');
        }

        $required = $usuario === null ? ['required'] : ['sometimes', 'required'];

        return $request->validate([
            'nome' => [...$required, 'string', 'max:100'],
            'cpf' => [...$required, 'string', 'max:14', $cpfRule],
            'email' => ['nullable', 'sometimes', 'email', 'max:100', $emailRule],
            'telefone' => ['nullable', 'sometimes', 'string', 'max:20'],
            'tipo_usuario' => [...$required, 'string', 'max:30'],
        ]);
    }

    private function authorizeFuncionario(Request $request): void
    {
        abort_unless($request->user() instanceof Funcionario, 403, 'Somente funcionários podem gerenciar usuários.');
    }
}
