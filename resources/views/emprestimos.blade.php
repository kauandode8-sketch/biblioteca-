<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Consulte seus empréstimos, reservas e histórico no Biblio.">
    <title>Meus empréstimos | Biblio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="{{ asset('sesi-theme.css') }}" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
    <header class="bg-white border-bottom">
        <nav class="navbar navbar-expand-lg container-xl py-2" aria-label="Navegação principal">
            <a href="{{ route('biblioteca.inicio') }}" class="navbar-brand d-inline-flex align-items-center gap-2 me-lg-5"><span class="badge bg-danger rounded-1">B</span><span>Biblio</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navegacao" aria-controls="navegacao" aria-expanded="false" aria-label="Abrir menu"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navegacao">
                <div class="navbar-nav me-auto">
                    <a class="nav-link" href="{{ route('biblioteca.inicio') }}">Início</a>
                    <a class="nav-link" href="{{ route('biblioteca.inicio') }}#categorias">Acervo</a>
                    <a class="nav-link active" aria-current="page" href="{{ route('emprestimos.index') }}">Meus empréstimos</a>
                    <a class="nav-link" href="{{ route('biblioteca.inicio') }}#listas">Minhas listas</a>
                </div>
                <div class="d-flex align-items-center gap-3 py-2 py-lg-0">
                    <a href="#ajuda" class="btn btn-light btn-sm rounded-circle" aria-label="Ajuda">?</a>
                    <a href="#notificacoes" class="btn btn-light btn-sm rounded-circle" aria-label="Notificações">♧</a>
                    <a href="#perfil" class="d-flex align-items-center gap-2 text-decoration-none text-dark"><span class="badge rounded-circle bg-dark">{{ strtoupper(substr(session('nome_usuario', 'B'), 0, 1)) }}</span><span class="small">{{ session('nome_usuario', 'Visitante') }}<br><span class="text-secondary">Leitor</span></span></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Sair</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main class="container-xl py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div><p class="small text-danger text-uppercase fw-semibold mb-1">Sua biblioteca</p><h1 class="h2 mb-1">Meus empréstimos</h1><p class="text-secondary mb-0">Acompanhe prazos, renove seus livros e consulte leituras anteriores.</p></div>
            <a href="{{ route('biblioteca.inicio') }}#categorias" class="btn btn-outline-secondary bg-white">⌕ &nbsp; Explorar o acervo</a>
        </div>

        <section class="row g-3 mb-4" aria-label="Resumo da sua biblioteca">
            <div class="col-6 col-xl-3"><div class="card h-100"><div class="card-body d-flex gap-3 align-items-center"><span class="badge rounded-circle bg-secondary-subtle text-secondary">▤</span><div><p class="h4 mb-0">3</p><p class="small mb-0">empréstimos ativos</p><p class="small text-secondary mb-0">Um livro disponível; mais 2 em prazo.</p></div></div></div></div>
            <div class="col-6 col-xl-3"><div class="card border-danger-subtle h-100"><div class="card-body d-flex gap-3 align-items-center"><span class="badge rounded-circle bg-danger-subtle text-danger">◷</span><div><p class="h4 mb-0">4 dias</p><p class="small mb-0">para o próximo prazo</p><p class="small text-secondary mb-0">Torto Arado · 9 out. 2026</p></div></div></div></div>
            <div class="col-6 col-xl-3"><div class="card h-100"><div class="card-body d-flex gap-3 align-items-center"><span class="badge rounded-circle bg-secondary-subtle text-secondary">↻</span><div><p class="h4 mb-0">3</p><p class="small mb-0">renovações disponíveis</p><p class="small text-secondary mb-0">Consulte as regras de cada exemplar.</p></div></div></div></div>
            <div class="col-6 col-xl-3"><div class="card h-100"><div class="card-body d-flex gap-3 align-items-center"><span class="badge rounded-circle bg-secondary-subtle text-secondary">▣</span><div><p class="h4 mb-0">18</p><p class="small mb-0">livros lidos em 2026</p><p class="small text-secondary mb-0">Você está construindo um ótimo ano.</p></div></div></div></div>
        </section>

        <ul class="nav nav-tabs mb-3" id="emprestimos-tabs" role="tablist">
            <li class="nav-item" role="presentation"><button class="nav-link active" id="ativos-tab" data-bs-toggle="tab" data-bs-target="#ativos" type="button" role="tab" aria-controls="ativos" aria-selected="true">Ativos <span class="badge bg-danger-subtle text-danger">3</span></button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="reservas-tab" data-bs-toggle="tab" data-bs-target="#reservas" type="button" role="tab" aria-controls="reservas" aria-selected="false">Reservas <span class="badge bg-danger-subtle text-danger">{{ $reservas->count() }}</span></button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="historico-tab" data-bs-toggle="tab" data-bs-target="#historico" type="button" role="tab" aria-controls="historico" aria-selected="false">Histórico</button></li>
        </ul>

        <div class="tab-content" id="emprestimos-tabs-content">
            <section class="tab-pane fade show active" id="ativos" role="tabpanel" aria-labelledby="ativos-tab" tabindex="0">
                <div class="row g-3">
                    <div class="col-lg-9"><div class="d-grid gap-3">
                        <article class="card border-danger-subtle shadow-sm"><div class="card-body p-3 p-md-4"><div class="row align-items-center g-3">
                            <div class="col-md-5"><p class="small text-danger text-uppercase fw-semibold mb-1">Literatura brasileira</p><h2 class="h5 mb-1">Torto Arado</h2><p class="small text-secondary mb-2">Itamar Vieira Junior</p><p class="small text-secondary mb-0">◷ &nbsp; Renovação disponível</p></div>
                            <div class="col-md-4"><div class="d-flex justify-content-between small"><span>Retirado</span><span>Devolução</span></div><div class="d-flex justify-content-between small fw-semibold"><span>22/09/2026</span><span>09/10/2026</span></div><div class="progress my-2" role="progressbar" aria-label="Prazo do empréstimo" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar bg-danger w-75"></div></div><p class="small text-danger mb-0">Vence em 4 dias</p></div>
                            <div class="col-md-3 d-grid gap-2"><button class="btn btn-danger btn-sm" type="button">↻ &nbsp; Renovar</button><a href="#detalhes-torto-arado" class="btn btn-link link-secondary btn-sm">Ver detalhes</a></div>
                        </div></div></article>
                        <article class="card shadow-sm"><div class="card-body p-3 p-md-4"><div class="row align-items-center g-3">
                            <div class="col-md-5"><p class="small text-danger text-uppercase fw-semibold mb-1">Design</p><h2 class="h5 mb-1">Design como atitude</h2><p class="small text-secondary mb-2">Alice Rawsthorn</p><p class="small text-secondary mb-0">◷ &nbsp; Uma renovação disponível</p></div>
                            <div class="col-md-4"><div class="d-flex justify-content-between small"><span>Retirado</span><span>Devolução</span></div><div class="d-flex justify-content-between small fw-semibold"><span>26/09/2026</span><span>19/10/2026</span></div><div class="progress my-2" role="progressbar" aria-label="Prazo do empréstimo" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar bg-success w-50"></div></div><p class="small text-success mb-0">Vence em 14 dias</p></div>
                            <div class="col-md-3 d-grid gap-2"><button class="btn btn-danger btn-sm" type="button">↻ &nbsp; Renovar</button><a href="#detalhes-design" class="btn btn-link link-secondary btn-sm">Ver detalhes</a></div>
                        </div></div></article>
                        <article class="card shadow-sm"><div class="card-body p-3 p-md-4"><div class="row align-items-center g-3">
                            <div class="col-md-5"><p class="small text-danger text-uppercase fw-semibold mb-1">Memórias</p><h2 class="h5 mb-1">Quarto de despejo</h2><p class="small text-secondary mb-2">Carolina Maria de Jesus</p><p class="small text-secondary mb-0">◷ &nbsp; 2 de 2 renovações usadas</p></div>
                            <div class="col-md-4"><div class="d-flex justify-content-between small"><span>Retirado</span><span>Devolução</span></div><div class="d-flex justify-content-between small fw-semibold"><span>28/09/2026</span><span>23/10/2026</span></div><div class="progress my-2" role="progressbar" aria-label="Prazo do empréstimo" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar bg-success w-25"></div></div><p class="small text-success mb-0">Vence em 18 dias</p></div>
                            <div class="col-md-3 d-grid gap-2"><button class="btn btn-outline-secondary btn-sm" type="button" disabled>Renovações esgotadas</button><a href="#detalhes-quarto" class="btn btn-link link-secondary btn-sm">Ver detalhes</a></div>
                        </div></div></article>
                    </div></div>
                    <aside class="col-lg-3">
                        <div class="card border-0 bg-dark text-white mb-3"><div class="card-body p-4"><span class="badge rounded-circle bg-danger mb-3">↻</span><h2 class="h5">Organize sua devolução</h2><p class="small text-white-50">Renove até 24 horas antes do prazo. Evite atrasos e mantenha seu histórico em dia.</p><div class="d-flex justify-content-between small border-bottom border-secondary py-2"><span>Biblioteca Central</span><span>09/10</span></div><div class="d-flex justify-content-between small border-bottom border-secondary py-2"><span>Biblioteca Central</span><span>19/10</span></div><div class="d-flex justify-content-between small py-2"><span>Biblioteca Central</span><span>23/10</span></div></div></div>
                        <div class="card"><div class="card-body p-4"><h2 class="h6">Como funcionam as renovações?</h2><ul class="list-unstyled small text-secondary mb-2"><li class="mb-2">✓ &nbsp; Até 2 renovações por exemplar</li><li class="mb-2">✓ &nbsp; Sem reservas pendentes de outros leitores</li><li>✓ &nbsp; Sem débitos ou atrasos em aberto</li></ul><a href="#regras" class="small link-danger text-decoration-none">Ver regulamento completo →</a></div></div>
                    </aside>
                </div>
            </section>

            <section class="tab-pane fade" id="reservas" role="tabpanel" aria-labelledby="reservas-tab" tabindex="0">
                <div class="d-grid gap-3">
                    @forelse ($reservas as $reserva)
                        <article class="card shadow-sm"><div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div><p class="small text-danger text-uppercase fw-semibold mb-1">Reserva confirmada</p><h2 class="h5 mb-1">{{ $reserva->livro->titulo }}</h2><p class="text-secondary mb-2">{{ $reserva->livro->autor }}</p><p class="small mb-0">Código: <strong>{{ $reserva->codigo }}</strong></p></div>
                            <div class="text-md-end"><p class="small text-secondary mb-1">Disponível para retirada até</p><p class="fw-semibold mb-2">{{ $reserva->expira_em->format('d/m/Y H:i') }}</p><a href="{{ route('livros.show', $reserva->livro) }}" class="btn btn-outline-danger btn-sm">Ver livro</a></div>
                        </div></article>
                    @empty
                        <div class="card"><div class="card-body p-4"><h2 class="h5">Você ainda não tem reservas ativas</h2><p class="text-secondary">Quando reservar um livro, ele aparecerá aqui com o código e o prazo para retirada.</p><a href="{{ route('biblioteca.inicio') }}#categorias" class="btn btn-outline-danger">Explorar o acervo</a></div></div>
                    @endforelse
                </div>
            </section>

            <section class="tab-pane fade" id="historico" role="tabpanel" aria-labelledby="historico-tab" tabindex="0">
                <div class="d-flex justify-content-between align-items-end mb-3"><div><p class="small text-danger text-uppercase fw-semibold mb-1">Últimas devoluções</p><h2 class="h5 mb-0">Histórico recente</h2></div><a href="#historico-completo" class="small link-danger text-decoration-none">Ver histórico completo →</a></div>
                <div class="table-responsive card"><table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th scope="col">Livro</th><th scope="col">Devolução</th><th scope="col">Situação</th><th scope="col" class="text-end">Ação</th></tr></thead>
                    <tbody>
                        <tr><td><strong>A hora da estrela</strong><br><span class="small text-secondary">Clarice Lispector</span></td><td>28/09/2026</td><td><span class="badge bg-success-subtle text-success">Devolvido no prazo</span></td><td class="text-end"><a href="{{ route('biblioteca.inicio') }}#categorias" class="link-danger small text-decoration-none">Emprestar novamente</a></td></tr>
                        <tr><td><strong>O avesso da pele</strong><br><span class="small text-secondary">Jeferson Tenório</span></td><td>12/09/2026</td><td><span class="badge bg-success-subtle text-success">Devolvido no prazo</span></td><td class="text-end"><a href="{{ route('biblioteca.inicio') }}#categorias" class="link-danger small text-decoration-none">Emprestar novamente</a></td></tr>
                        <tr><td><strong>Ensaio sobre a cegueira</strong><br><span class="small text-secondary">José Saramago</span></td><td>20/08/2026</td><td><span class="badge bg-success-subtle text-success">Devolvido no prazo</span></td><td class="text-end"><a href="{{ route('biblioteca.inicio') }}#categorias" class="link-danger small text-decoration-none">Emprestar novamente</a></td></tr>
                        <tr><td><strong>Pequeno manual antirracista</strong><br><span class="small text-secondary">Djamila Ribeiro</span></td><td>03/08/2026</td><td><span class="badge bg-danger-subtle text-danger">Devolvido com 1 dia de atraso</span></td><td class="text-end"><a href="{{ route('biblioteca.inicio') }}#categorias" class="link-danger small text-decoration-none">Emprestar novamente</a></td></tr>
                    </tbody>
                </table></div>
            </section>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
