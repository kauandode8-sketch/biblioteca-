<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use App\Models\Livro;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LivroController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Livro::query()->orderBy('titulo')->paginate(20));
    }

    public function show(Livro $livro): JsonResponse
    {
        return response()->json(['livro' => $livro]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeFuncionario($request);

        $dados = $request->validate($this->rules());
        $livro = Livro::query()->create($dados);

        return response()->json([
            'message' => 'Livro criado com sucesso.',
            'livro' => $livro,
        ], 201);
    }

    public function update(Request $request, Livro $livro): JsonResponse
    {
        $this->authorizeFuncionario($request);

        $dados = $request->validate($this->rules($livro));
        $livro->update($dados);

        return response()->json([
            'message' => 'Livro atualizado com sucesso.',
            'livro' => $livro->refresh(),
        ]);
    }

    public function destroy(Request $request, Livro $livro): JsonResponse
    {
        $this->authorizeFuncionario($request);

        abort_if($livro->reservas()->exists(), 409, 'Não é possível excluir um livro que possui reservas registradas.');

        $livro->delete();

        return response()->json(['message' => 'Livro excluído com sucesso.']);
    }

    /** @return array<string, array<int, mixed>> */
    private function rules(?Livro $livro = null): array
    {
        $slugRule = Rule::unique('livros', 'slug');

        if ($livro !== null) {
            $slugRule->ignore($livro->getKey());
        }

        return [
            'slug' => ['required', 'string', 'max:255', $slugRule],
            'titulo' => ['required', 'string', 'max:255'],
            'autor' => ['required', 'string', 'max:255'],
            'editora' => ['nullable', 'string', 'max:255'],
            'ano_publicacao' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'paginas' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'isbn' => ['nullable', 'string', 'max:255'],
            'sinopse' => ['required', 'string'],
            'categoria' => ['required', 'string', 'max:255'],
            'assuntos' => ['nullable', 'array'],
            'assuntos.*' => ['string', 'max:255'],
            'classificacao' => ['nullable', 'string', 'max:255'],
            'localizacao' => ['required', 'string', 'max:255'],
            'exemplares_totais' => ['required', 'integer', 'min:1', 'max:65535'],
            'exemplares_emprestados' => ['required', 'integer', 'min:0', 'lte:exemplares_totais', 'max:65535'],
        ];
    }

    private function authorizeFuncionario(Request $request): void
    {
        abort_unless($request->user() instanceof Funcionario, 403, 'Somente funcionários podem gerenciar o acervo.');
    }
}
