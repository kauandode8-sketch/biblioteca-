<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Consulte suas reservas de livros no Biblio.">
    <title>Minhas reservas | Biblio</title>
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
                    <a class="nav-link active" aria-current="page" href="{{ route('emprestimos.index') }}">Minhas reservas</a>
                </div>
                <div class="d-flex align-items-center gap-3 py-2 py-lg-0">
                    <span class="d-flex align-items-center gap-2"><span class="badge rounded-circle bg-dark">{{ strtoupper(substr(session('nome_usuario', 'L'), 0, 1)) }}</span><span class="small">{{ session('nome_usuario', 'Leitor') }}<br><span class="text-secondary">Leitor</span></span></span>
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
            <div>
                <p class="small text-danger text-uppercase fw-semibold mb-1">Sua biblioteca</p>
                <h1 class="h2 mb-1">Minhas reservas</h1>
                <p class="text-secondary mb-0">Acompanhe os livros reservados e o prazo para retirada.</p>
            </div>
            <a href="{{ route('biblioteca.inicio') }}#categorias" class="btn btn-outline-secondary bg-white">Explorar o acervo</a>
        </div>

        <div class="alert alert-info" role="status">
            Esta página exibe suas reservas. Os empréstimos individuais ainda não são registrados pelo sistema.
        </div>

        <section aria-labelledby="reservas-heading">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 id="reservas-heading" class="h4 mb-0">Reservas ativas</h2>
                <span class="badge bg-danger-subtle text-danger">{{ $reservas->count() }}</span>
            </div>

            <div class="d-grid gap-3">
                @forelse ($reservas as $reserva)
                    <article class="card shadow-sm">
                        <div class="card-body p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <p class="small text-danger text-uppercase fw-semibold mb-1">Reserva confirmada</p>
                                <h3 class="h5 mb-1">{{ $reserva->livro?->titulo ?? 'Livro indisponível' }}</h3>
                                @if ($reserva->livro)
                                    <p class="text-secondary mb-2">{{ $reserva->livro->autor }}</p>
                                    <a href="{{ route('livros.show', $reserva->livro) }}" class="btn btn-outline-danger btn-sm">Ver livro</a>
                                @endif
                                <p class="small mb-0 mt-2">Código: <strong>{{ $reserva->codigo }}</strong></p>
                            </div>
                            <div>
                                <p class="small text-secondary mb-1">Disponível para retirada até</p>
                                <p class="fw-semibold mb-0">{{ $reserva->expira_em->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="card">
                        <div class="card-body p-4">
                            <h3 class="h5">Você ainda não tem reservas ativas</h3>
                            <p class="text-secondary">Quando reservar um livro, ele aparecerá aqui com o código e o prazo para retirada.</p>
                            <a href="{{ route('biblioteca.inicio') }}#categorias" class="btn btn-outline-danger">Explorar o acervo</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
