<?php

namespace App\Http\Controllers\Api\Biblioteca;

use App\Http\Controllers\Controller;
use App\Models\Biblioteca\Livro;
use App\Models\Funcionario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LivroController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'id_categoria' => ['sometimes', 'integer', Rule::exists('biblioteca.categoria', 'id_categoria')],
        ]);

        $consulta = Livro::query()->with('categoria')->orderBy('titulo');

        if (isset($dados['id_categoria'])) {
            $consulta->where('id_categoria', $dados['id_categoria']);
        }

        return response()->json($consulta->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeFuncionario($request);
        $dados = $request->validate($this->rules());
        $livro = Livro::query()->create($dados)->load('categoria');

        return response()->json([
            'message' => 'Livro cadastrado com sucesso.',
            'data' => $livro,
        ], 201);
    }

    public function show(Livro $livro): JsonResponse
    {
        return response()->json(['data' => $livro->load('categoria')]);
    }

    public function update(Request $request, Livro $livro): JsonResponse
    {
        $this->authorizeFuncionario($request);

        if ($request->exists('quantidade') || $request->exists('disponivel')) {
            $request->merge([
                'quantidade' => $request->input('quantidade', $livro->quantidade),
                'disponivel' => $request->input('disponivel', $livro->disponivel),
            ]);
        }

        $dados = $request->validate($this->rules($livro));
        $livro->update($dados);

        return response()->json([
            'message' => 'Livro atualizado com sucesso.',
            'data' => $livro->refresh()->load('categoria'),
        ]);
    }

    public function destroy(Request $request, Livro $livro): JsonResponse
    {
        $this->authorizeFuncionario($request);
        $livro->delete();

        return response()->json(['message' => 'Livro excluído com sucesso.']);
    }

    /** @return array<string, array<int, mixed>> */
    private function rules(?Livro $livro = null): array
    {
        $isbnRule = Rule::unique('biblioteca.livro', 'isbn');

        if ($livro !== null) {
            $isbnRule->ignore($livro->getKey(), 'id_livro');
        }

        $required = $livro === null ? ['required'] : ['sometimes', 'required'];

        return [
            'id_categoria' => [...$required, 'integer', Rule::exists('biblioteca.categoria', 'id_categoria')],
            'titulo' => [...$required, 'string', 'max:150'],
            'autor' => [...$required, 'string', 'max:100'],
            'isbn' => ['nullable', 'sometimes', 'string', 'max:20', $isbnRule],
            'ano_publicacao' => ['nullable', 'sometimes', 'integer', 'between:1901,2155'],
            'quantidade' => ['sometimes', 'required', 'integer', 'min:0'],
            'disponivel' => ['sometimes', 'required', 'integer', 'min:0', 'lte:quantidade'],
        ];
    }

    private function authorizeFuncionario(Request $request): void
    {
        abort_unless($request->user() instanceof Funcionario, 403, 'Somente funcionários podem gerenciar livros.');
    }
}
