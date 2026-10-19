<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Detalhes e reserva de {{ $livro->titulo }} no Biblio.">
    <title>{{ $livro->titulo }} | Biblio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="{{ asset('sesi-theme.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body class="bg-body-tertiary">
    <header class="bg-white border-bottom">
        <nav class="navbar navbar-expand-lg container-xl py-2" aria-label="Navegação principal">
            <a href="{{ route('biblioteca.inicio') }}" class="navbar-brand d-inline-flex align-items-center gap-2 me-lg-5">
                <span class="badge bg-danger rounded-1">B</span>
                <span>Biblio</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navegacao" aria-controls="navegacao" aria-expanded="false" aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navegacao">
                <div class="navbar-nav me-auto">
                    <a class="nav-link" href="{{ route('biblioteca.inicio') }}">Início</a>
                    <a class="nav-link active" aria-current="page" href="{{ route('livros.index') }}#recomendacoes">Acervo</a>
                    <a class="nav-link" href="{{ route('emprestimos.index') }}">Meus empréstimos</a>
                    <a class="nav-link" href="{{ route('biblioteca.inicio') }}#listas">Minhas listas</a>
                </div>
                <div class="d-flex align-items-center gap-3 py-2">
                    <a href="#perfil" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                        <span class="badge rounded-circle bg-dark">{{ strtoupper(substr(session('nome_usuario', 'B'), 0, 1)) }}</span>
                        <span class="small">{{ session('nome_usuario', 'Leitor') }}<br><span class="text-secondary">Leitor</span></span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Sair</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main class="container-xl py-4">
        <nav aria-label="Trilha de navegação">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('biblioteca.inicio') }}" class="link-secondary">Início</a></li>
                <li class="breadcrumb-item"><a href="{{ route('livros.index') }}#recomendacoes" class="link-secondary">{{ $livro->categoria }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $livro->titulo }}</li>
            </ol>
        </nav>

        @if (session('reserva_codigo'))
            <div class="alert alert-success" role="status">
                Reserva confirmada. Seu código é <strong>{{ session('reserva_codigo') }}</strong>.
                Retire o exemplar até {{ session('reserva_expira_em') }}.
            </div>
        @endif

        @if ($errors->has('reserva'))
            <div class="alert alert-warning" role="alert">{{ $errors->first('reserva') }}</div>
        @endif

        <section class="card border-0 shadow-sm rounded-4 mb-4" aria-labelledby="titulo-livro">
            <div class="row g-0">
                <div class="col-lg-8">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex gap-2 mb-3">
                            <span class="badge bg-danger-subtle text-danger">{{ $livro->categoria }}</span>
                        </div>

                        <h1 id="titulo-livro" class="display-6 mb-1">{{ $livro->titulo }}</h1>
                        <p class="mb-3">por <span class="text-danger">{{ $livro->autor }}</span></p>

                        <div class="d-flex align-items-center gap-2 mb-3" aria-label="Avaliação média de 4,8 de 5">
                            <span class="text-danger" aria-hidden="true">★★★★★</span>
                            <span class="small text-secondary">4,8 · 216 avaliações</span>
                        </div>

                        <p class="text-secondary fst-italic mb-4">“{{ $livro->sinopse }}”</p>

                        <div class="row row-cols-2 row-cols-md-4 g-3 border-top pt-3">
                            <div class="col"><p class="small text-secondary mb-1">Editora</p><p class="small fw-semibold mb-0">{{ $livro->editora ?? 'Não informada' }}</p></div>
                            <div class="col"><p class="small text-secondary mb-1">Publicação</p><p class="small fw-semibold mb-0">{{ $livro->ano_publicacao ?? '—' }}</p></div>
                            <div class="col"><p class="small text-secondary mb-1">Páginas</p><p class="small fw-semibold mb-0">{{ $livro->paginas ?? '—' }}</p></div>
                            <div class="col"><p class="small text-secondary mb-1">ISBN</p><p class="small fw-semibold mb-0">{{ $livro->isbn ?? '—' }}</p></div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-sm">♡ &nbsp; Salvar</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm">↗ &nbsp; Compartilhar</button>
                        </div>
                    </div>
                </div>

                <aside class="col-lg-4 p-3 p-md-4" aria-label="Disponibilidade e reserva">
                    <div class="card h-100 border-0 bg-body-tertiary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                <span class="small fw-semibold {{ $exemplaresDisponiveis > 0 ? 'text-success' : 'text-danger' }}">
                                    ● {{ $exemplaresDisponiveis > 0 ? 'Disponível' : 'Indisponível' }}
                                </span>
                                <span class="small text-secondary">{{ $exemplaresDisponiveis }} de {{ $livro->exemplares_totais }}</span>
                            </div>

                            <dl class="small mb-3">
                                <dt class="text-secondary text-uppercase">Localização</dt>
                                <dd>{{ $livro->localizacao }}</dd>
                                <dt class="text-secondary text-uppercase">Retirada</dt>
                                <dd>Disponível por até 48 horas após a reserva.</dd>
                            </dl>

                            <p class="small text-secondary">A reserva será vinculada à sua conta: <strong>{{ session('nome_usuario') }}</strong> ({{ session('email_usuario') }}).</p>
                            <form method="POST" action="{{ route('reservas.store', $livro) }}">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" @disabled($exemplaresDisponiveis < 1)>Reservar exemplar</button>
                            </form>
                            <p class="small text-secondary mt-2 mb-0">A reserva fica disponível para retirada durante 48 horas.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <div class="row g-3">
            <section class="col-lg-8" aria-labelledby="titulo-sinopse">
                <h2 id="titulo-sinopse" class="h4">Sinopse</h2>
                <p>{{ $livro->sinopse }}</p>
                <p>Uma leitura para ampliar perspectivas e refletir sobre as relações entre cultura, sociedade e natureza.</p>
                <h2 class="h5 mt-4">Leituras relacionadas</h2>
                <a href="{{ route('biblioteca.inicio') }}#recomendacoes" class="link-danger text-decoration-none">Voltar às recomendações →</a>
            </section>

            <aside class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h6">Assuntos e classificação</h2>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach ($livro->assuntos ?? [] as $assunto)
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary">{{ $assunto }}</span>
                            @endforeach
                        </div>
                        <hr>
                        <p class="small text-secondary text-uppercase mb-1">Classificação</p>
                        <p class="small mb-0">{{ $livro->classificacao ?? 'Não informada' }}</p>
                    </div>
                </div>
            </aside>
        </div>
    </main>
</body>
</html>

