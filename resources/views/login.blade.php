<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Entre no Biblio para acessar o acervo e acompanhar seus empréstimos.">
    <title>Entrar | Biblio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="{{ asset('sesi-theme.css') }}" rel="stylesheet">
</head>
<body>
    <main class="login-page container-fluid min-vh-100 d-flex bg-dark p-3 p-lg-4">
        <div class="login-layout row g-0 flex-grow-1 overflow-hidden rounded-4 shadow-lg">
            <section class="login-form-panel col-lg-5 d-flex flex-column justify-content-between bg-white p-4 p-md-5">
                <a href="{{ route('home') }}" class="login-brand d-inline-flex align-items-center gap-3 text-dark text-decoration-none">
                    <span class="login-brand-mark badge bg-danger rounded-3 fs-5">B</span>
                    <span class="fs-4 fw-bold">Biblio</span>
                </a>

                <div class="login-form-content">
                    <p class="login-kicker mb-3"><span aria-hidden="true"></span> SEU ESPAÇO DE LEITURA</p>
                    <h1 class="login-heading mb-2">Bem-vindo à sua biblioteca.</h1>
                    <p class="login-description text-secondary mb-4">Entre para explorar o acervo, acompanhar seus empréstimos e descobrir sua próxima leitura.</p>
                    @if (session('status'))
                        <div class="alert alert-info" role="status">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label small text-secondary mb-1">E-mail institucional</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-secondary" aria-hidden="true">@</span>
                                    <input type="email" class="form-control bg-white" id="email" name="email" value="{{ old('email') }}" placeholder="nome@instituicao.edu.br" autocomplete="email" required>
                                </div>
                                @error('email')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="senha" class="form-label small text-secondary mb-1">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-secondary" aria-hidden="true">●</span>
                                    <input type="password" class="form-control bg-white" id="senha" name="senha" placeholder="Digite sua senha" autocomplete="current-password" required>
                                </div>
                                @error('senha')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="lembrar" name="lembrar">
                                    <label class="form-check-label small text-secondary" for="lembrar">Manter meu acesso</label>
                                </div>
                            </div>

                        <button type="submit" class="login-submit btn btn-danger w-100 mb-3">Entrar no Biblio <span aria-hidden="true">→</span></button>
                        <p class="small text-center mb-3">Ainda não tem cadastro? <a href="{{ route('usuarios.create') }}" class="link-danger">Criar conta</a></p>

                    </form>
                </div>

                <p class="login-help small text-secondary mb-0"><span aria-hidden="true">ⓘ</span> Problemas para acessar? Fale com a equipe da biblioteca.</p>
            </section>

            <section class="login-visual col-lg-7 d-flex flex-column justify-content-between text-white p-4 p-md-5 position-relative overflow-hidden">
                <img src="{{ asset('image.png') }}" alt="" aria-hidden="true" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
                <div class="login-visual-overlay position-absolute top-0 start-0 w-100 h-100" aria-hidden="true"></div>

                <p class="login-location small text-uppercase mb-0 position-relative z-1"><span aria-hidden="true"></span> Biblioteca Central <span class="opacity-75">· Campus Sul</span></p>

                <figure class="login-quote mb-0 position-relative z-1">
                    <blockquote class="mb-3">
                        <p class="login-quote-mark mb-2" aria-hidden="true">“</p>
                        <p class="login-quote-text">Uma biblioteca não é um luxo, mas uma das necessidades da vida.</p>
                    </blockquote>
                    <figcaption class="login-quote-author">Henry Ward Beecher</figcaption>
                </figure>
            </section>
        </div>
    </main>
</body>
</html>
