<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FuncionarioController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'email' => ['required', 'email'],
            'senha' => ['required', 'string'],
        ]);

        $funcionario = Funcionario::query()
            ->where('email', Str::lower($dados['email']))
            ->first();

        if ($funcionario === null || ! Hash::check($dados['senha'], $funcionario->password)) {
            return response()->json(['message' => 'E-mail ou senha inválidos.'], 401);
        }

        return response()->json([
            'funcionario' => $funcionario->only(['id', 'name', 'email']),
            'token' => $funcionario->createToken('thunder-client')->plainTextToken,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorizeFuncionario($request);

        return response()->json(Funcionario::query()->orderBy('name')->paginate(20));
    }

    public function show(Request $request, Funcionario $funcionario): JsonResponse
    {
        $this->authorizeFuncionario($request);

        return response()->json(['funcionario' => $funcionario->only(['id', 'name', 'email', 'created_at'])]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeFuncionario($request);

        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:funcionarios,email'],
            'senha' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $funcionario = Funcionario::query()->create([
            'name' => Str::squish($dados['name']),
            'email' => Str::lower($dados['email']),
            'password' => $dados['senha'],
        ]);

        return response()->json([
            'message' => 'Funcionário criado com sucesso.',
            'funcionario' => $funcionario->only(['id', 'name', 'email']),
        ], 201);
    }

    public function update(Request $request, Funcionario $funcionario): JsonResponse
    {
        $this->authorizeFuncionario($request);

        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('funcionarios', 'email')->ignore($funcionario->getKey())],
            'senha' => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $funcionario->fill([
            'name' => Str::squish($dados['name']),
            'email' => Str::lower($dados['email']),
        ]);

        if (! empty($dados['senha'])) {
            $funcionario->password = $dados['senha'];
        }

        $funcionario->save();

        return response()->json([
            'message' => 'Funcionário atualizado com sucesso.',
            'funcionario' => $funcionario->fresh()->only(['id', 'name', 'email']),
        ]);
    }

    public function destroy(Request $request, Funcionario $funcionario): JsonResponse
    {
        $this->authorizeFuncionario($request);

        abort_if(Funcionario::query()->count() <= 1, 409, 'Não é possível excluir o último funcionário.');

        $funcionario->tokens()->delete();
        $funcionario->delete();

        return response()->json(['message' => 'Funcionário excluído com sucesso.']);
    }

    private function authorizeFuncionario(Request $request): void
    {
        abort_unless($request->user() instanceof Funcionario, 403, 'Somente funcionários podem gerenciar funcionários.');
    }
}
