<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Explore o acervo e descubra sua próxima leitura no Biblio.">
    <title>Biblio | Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="{{ asset('sesi-theme.css') }}" rel="stylesheet">
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
                    <a class="nav-link active" aria-current="page" href="{{ route('biblioteca.inicio') }}">Início</a>
                    <a class="nav-link" href="{{ route('livros.index') }}#recomendacoes">Acervo</a>
                    <a class="nav-link" href="{{ route('emprestimos.index') }}">Meus empréstimos</a>
                    <a class="nav-link" href="#listas">Minhas listas</a>
                </div>
                <div class="d-flex align-items-center gap-3 py-2 py-lg-0">
                    <a href="#ajuda" class="btn btn-light btn-sm rounded-circle" aria-label="Ajuda">?</a>
                    <a href="#notificacoes" class="btn btn-light btn-sm rounded-circle" aria-label="Notificações">♧</a>
                    <a href="#perfil" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                        <span class="badge rounded-circle bg-dark">{{ strtoupper(substr($nomeUsuario, 0, 1)) }}</span>
                        <span class="small">{{ $nomeUsuario }}<br><span class="text-secondary">Graduação</span></span>
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
        <section class="row g-0 align-items-center rounded-4 bg-dark text-white overflow-hidden mb-4" aria-labelledby="boas-vindas">
            <div class="col-lg-8 p-4 p-md-5">
                <p class="small text-uppercase text-white-50 mb-2">Biblioteca Central · seu espaço de leitura</p>
                <h1 id="boas-vindas" class="h2 mb-2">Olá, {{ $nomeUsuario }}. O que vamos descobrir hoje?</h1>
                <form class="mt-3" role="search">
                    <label for="busca" class="visually-hidden">Buscar no acervo</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-secondary" aria-hidden="true">⌕</span>
                        <input id="busca" type="search" class="form-control" placeholder="Buscar por título, autor ou assunto">
                        <button class="btn btn-light" type="button" aria-label="Buscar">Buscar</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 p-4">
                <div id="emprestimos" class="card border-0 bg-body-tertiary">
                    <div class="card-body">
                        <p class="small text-danger text-uppercase fw-semibold mb-2">Sua semana</p>
                        <p class="display-6 text-dark mb-0">3 livros</p>
                        <p class="small text-secondary mb-0">em empréstimo, com próximo prazo em 4 dias.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="categorias" class="mb-4" aria-labelledby="titulo-categorias">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <h2 id="titulo-categorias" class="h5 mb-0">Explore por categoria</h2>
                <a href="{{ route('livros.index') }}#recomendacoes" class="small link-danger text-decoration-none">Ver todas →</a>
            </div>
            <div class="row g-2">
                <div class="col-6 col-md-4 col-xl-2"><a href="#recomendacoes" class="card h-100 text-decoration-none text-dark"><div class="card-body p-3"><span class="badge rounded-circle bg-danger-subtle text-danger mb-2">L</span><h3 class="h6 mb-1">Literatura</h3><p class="small text-secondary mb-0">8.420 livros</p></div></a></div>
                <div class="col-6 col-md-4 col-xl-2"><a href="#recomendacoes" class="card h-100 text-decoration-none text-dark"><div class="card-body p-3"><span class="badge rounded-circle bg-danger-subtle text-danger mb-2">C</span><h3 class="h6 mb-1">Ciências</h3><p class="small text-secondary mb-0">5.768 livros</p></div></a></div>
                <div class="col-6 col-md-4 col-xl-2"><a href="#recomendacoes" class="card h-100 text-decoration-none text-dark"><div class="card-body p-3"><span class="badge rounded-circle bg-danger-subtle text-danger mb-2">H</span><h3 class="h6 mb-1">Humanidades</h3><p class="small text-secondary mb-0">7.210 livros</p></div></a></div>
                <div class="col-6 col-md-4 col-xl-2"><a href="#recomendacoes" class="card h-100 text-decoration-none text-dark"><div class="card-body p-3"><span class="badge rounded-circle bg-danger-subtle text-danger mb-2">A</span><h3 class="h6 mb-1">Artes</h3><p class="small text-secondary mb-0">3.380 livros</p></div></a></div>
                <div class="col-6 col-md-4 col-xl-2"><a href="#recomendacoes" class="card h-100 text-decoration-none text-dark"><div class="card-body p-3"><span class="badge rounded-circle bg-danger-subtle text-danger mb-2">T</span><h3 class="h6 mb-1">Tecnologia</h3><p class="small text-secondary mb-0">4.860 livros</p></div></a></div>
                <div class="col-6 col-md-4 col-xl-2"><a href="#recomendacoes" class="card h-100 text-decoration-none text-dark"><div class="card-body p-3"><span class="badge rounded-circle bg-danger-subtle text-danger mb-2">I</span><h3 class="h6 mb-1">Idiomas</h3><p class="small text-secondary mb-0">2.540 livros</p></div></a></div>
            </div>
        </section>

        <section id="recomendacoes" class="mb-4" aria-labelledby="titulo-recomendacoes">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <div><p class="small text-danger text-uppercase fw-semibold mb-1">Seleção para você</p><h2 id="titulo-recomendacoes" class="h5 mb-0">Recomendações da semana</h2></div>
                <a href="#mais-lidos" class="small link-danger text-decoration-none">Ver mais recomendações →</a>
            </div>
            <div class="row g-2">
                @forelse ($livros as $livro)
                    <article class="col-6 col-md-4 col-xl-2">
                        <div class="h-100 border rounded bg-white p-3">
                            <p class="small text-danger text-uppercase mb-1">{{ $livro->categoria }}</p>
                            <h3 class="h6 mb-1">
                                <a href="{{ route('livros.show', $livro) }}" class="text-dark text-decoration-none">{{ $livro->titulo }}</a>
                            </h3>
                            <p class="small text-secondary mb-0">{{ $livro->autor }}</p>
                        </div>
                    </article>
                @empty
                    <p class="col-12 text-secondary">Nenhum livro cadastrado no acervo.</p>
                @endforelse
            </div>
        </section>

        <div class="row g-3">
            <section id="ranking" class="col-lg-8" aria-labelledby="titulo-mais-lidos">
                <div class="card h-100">
                    <div class="card-body p-3 p-md-4">
                        <p class="small text-danger text-uppercase fw-semibold mb-1">Comunidade Biblio</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 id="titulo-mais-lidos" class="h5 mb-0">Os mais lidos do mês</h2>
                            <a href="#ranking" class="small link-danger text-decoration-none">Ranking completo →</a>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 d-flex align-items-center gap-3"><span class="text-secondary">01</span><div class="flex-grow-1"><h3 class="h6 mb-1">Ideias para adiar o fim do mundo</h3><p class="small text-secondary mb-0">Ailton Krenak</p></div><span class="small text-secondary">124 empréstimos</span></div>
                            <div class="list-group-item px-0 d-flex align-items-center gap-3"><span class="text-secondary">02</span><div class="flex-grow-1"><h3 class="h6 mb-1">Torto Arado</h3><p class="small text-secondary mb-0">Itamar Vieira Junior</p></div><span class="small text-secondary">98 empréstimos</span></div>
                            <div class="list-group-item px-0 d-flex align-items-center gap-3"><span class="text-secondary">03</span><div class="flex-grow-1"><h3 class="h6 mb-1">Cem anos de solidão</h3><p class="small text-secondary mb-0">Gabriel García Márquez</p></div><span class="small text-secondary">87 empréstimos</span></div>
                        </div>
                    </div>
                </div>
            </section>

            <aside id="listas" class="col-lg-4">
                <div class="card h-100 border-0 bg-danger text-white">
                    <div class="card-body d-flex flex-column align-items-start p-4">
                        <span class="badge rounded-pill bg-light text-secondary mb-4">Novidades abertas</span>
                        <h2 class="h4 mt-auto">Clube de leitura: vozes latino-americanas</h2>
                        <p class="small">Encontros quinzenais, mediação de professores e uma curadoria para atravessar fronteiras.</p>
                        <a href="#clube" class="link-light small text-decoration-none mt-3">Conhecer o clube →</a>
                    </div>
                </div>
            </aside>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>



