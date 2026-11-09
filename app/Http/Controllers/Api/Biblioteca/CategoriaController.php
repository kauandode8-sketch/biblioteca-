<?php

namespace App\Http\Controllers\Api\Biblioteca;

use App\Http\Controllers\Controller;
use App\Models\Biblioteca\Categoria;
use App\Models\Funcionario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Categoria::query()->orderBy('nome')->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeFuncionario($request);
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'descricao' => ['nullable', 'string', 'max:255'],
        ]);

        $categoria = Categoria::query()->create($dados);

        return response()->json([
            'message' => 'Categoria cadastrada com sucesso.',
            'data' => $categoria,
        ], 201);
    }

    public function show(Categoria $categoria): JsonResponse
    {
        return response()->json(['data' => $categoria]);
    }

    public function update(Request $request, Categoria $categoria): JsonResponse
    {
        $this->authorizeFuncionario($request);
        $dados = $request->validate([
            'nome' => ['sometimes', 'required', 'string', 'max:100'],
            'descricao' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $categoria->update($dados);

        return response()->json([
            'message' => 'Categoria atualizada com sucesso.',
            'data' => $categoria->refresh(),
        ]);
    }

    public function destroy(Request $request, Categoria $categoria): JsonResponse
    {
        $this->authorizeFuncionario($request);
        abort_if($categoria->livros()->exists(), 409, 'Não é possível excluir uma categoria associada a livros.');

        $categoria->delete();

        return response()->json(['message' => 'Categoria excluída com sucesso.']);
    }

    private function authorizeFuncionario(Request $request): void
    {
        abort_unless($request->user() instanceof Funcionario, 403, 'Somente funcionários podem gerenciar categorias.');
    }
}
