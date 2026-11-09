<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar livro | Biblio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('sesi-theme.css') }}" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
    <header class="bg-white border-bottom">
        <nav class="navbar container-xl py-2">
            <a href="{{ route('livros.index') }}" class="navbar-brand">Biblio</a>
            <form method="POST" action="{{ route('funcionarios.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">Sair da equipe</button>
            </form>
        </nav>
    </header>
    <main class="container-xl py-4" style="max-width: 960px">
        @php($estaEditando = $livro !== null)
        <a href="{{ route('livros.index') }}" class="small text-danger text-decoration-none">← Voltar ao acervo</a>
        <div class="my-3">
            <p class="small text-danger text-uppercase fw-semibold mb-1">Acervo da biblioteca</p>
            <h1 class="h2">{{ $estaEditando ? 'Editar livro' : 'Cadastrar livro' }}</h1>
            <p class="text-secondary">Preencha os dados para incluir o livro no catálogo.</p>
        </div>
        <form method="POST" action="{{ $estaEditando ? route('funcionario.livros.update', $livro) : route('funcionario.livros.store') }}" class="card border-0 shadow-sm rounded-4">
            @csrf
            @if ($estaEditando)
                @method('PUT')
            @endif
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">Confira os campos destacados e tente novamente.</div>
                @endif
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="titulo" class="form-label">Título *</label>
                        <input id="titulo" name="titulo" value="{{ old('titulo', $livro?->titulo) }}" class="form-control @error('titulo') is-invalid @enderror" required>
                        @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="autor" class="form-label">Autor *</label>
                        <input id="autor" name="autor" value="{{ old('autor', $livro?->autor) }}" class="form-control @error('autor') is-invalid @enderror" required>
                        @error('autor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="editora" class="form-label">Editora</label>
                        <input id="editora" name="editora" value="{{ old('editora', $livro?->editora) }}" class="form-control @error('editora') is-invalid @enderror">
                        @error('editora')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label for="ano_publicacao" class="form-label">Ano</label>
                        <input id="ano_publicacao" name="ano_publicacao" type="number" min="0" max="65535" value="{{ old('ano_publicacao', $livro?->ano_publicacao) }}" class="form-control @error('ano_publicacao') is-invalid @enderror">
                        @error('ano_publicacao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label for="paginas" class="form-label">Páginas</label>
                        <input id="paginas" name="paginas" type="number" min="1" max="65535" value="{{ old('paginas', $livro?->paginas) }}" class="form-control @error('paginas') is-invalid @enderror">
                        @error('paginas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input id="isbn" name="isbn" value="{{ old('isbn', $livro?->isbn) }}" class="form-control @error('isbn') is-invalid @enderror">
                        @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="categoria" class="form-label">Categoria *</label>
                        <input id="categoria" name="categoria" value="{{ old('categoria', $livro?->categoria) }}" class="form-control @error('categoria') is-invalid @enderror" required>
                        @error('categoria')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="classificacao" class="form-label">Classificação indicativa</label>
                        <input id="classificacao" name="classificacao" value="{{ old('classificacao', $livro?->classificacao) }}" class="form-control @error('classificacao') is-invalid @enderror">
                        @error('classificacao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="sinopse" class="form-label">Sinopse *</label>
                        <textarea id="sinopse" name="sinopse" rows="4" class="form-control @error('sinopse') is-invalid @enderror" required>{{ old('sinopse', $livro?->sinopse) }}</textarea>
                        @error('sinopse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="localizacao" class="form-label">Localização na biblioteca *</label>
                        <input id="localizacao" name="localizacao" value="{{ old('localizacao', $livro?->localizacao) }}" placeholder="Ex.: Estante A, prateleira 2" class="form-control @error('localizacao') is-invalid @enderror" required>
                        @error('localizacao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label for="exemplares_totais" class="form-label">Exemplares totais *</label>
                        <input id="exemplares_totais" name="exemplares_totais" type="number" min="1" max="65535" value="{{ old('exemplares_totais', $livro?->exemplares_totais ?? 1) }}" class="form-control @error('exemplares_totais') is-invalid @enderror" required>
                        @error('exemplares_totais')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label for="exemplares_emprestados" class="form-label">Já emprestados</label>
                        <input id="exemplares_emprestados" name="exemplares_emprestados" type="number" min="0" value="{{ old('exemplares_emprestados', $livro?->exemplares_emprestados ?? 0) }}" class="form-control @error('exemplares_emprestados') is-invalid @enderror">
                        @error('exemplares_emprestados')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <span class="form-label d-block">Assuntos</span>
                        <div class="row g-2">
                            @foreach (old('assuntos', $livro?->assuntos ?? ['', '', '']) as $indice => $assunto)
                                <div class="col-md-4">
                                    <label for="assuntos-{{ $indice }}" class="visually-hidden">Assunto {{ $indice + 1 }}</label>
                                    <input id="assuntos-{{ $indice }}" name="assuntos[]" value="{{ $assunto }}" class="form-control" maxlength="100" placeholder="Assunto {{ $indice + 1 }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0 px-4 pb-4 d-flex justify-content-end gap-2">
                <a href="{{ $estaEditando ? route('livros.show', $livro) : route('livros.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-danger">{{ $estaEditando ? 'Salvar alterações' : 'Cadastrar livro' }}</button>
            </div>
        </form>
    </main>
</body>
</html>
