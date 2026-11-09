<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso da equipe | Biblio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('sesi-theme.css') }}" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <section class="card border-0 shadow-sm rounded-4 w-100" style="max-width: 460px">
            <div class="card-body p-4 p-md-5">
                <a href="{{ route('livros.index') }}" class="text-danger text-decoration-none small">← Voltar ao acervo</a>
                <p class="small text-danger text-uppercase fw-semibold mt-4 mb-2">Área da equipe</p>
                <h1 class="h3 mb-2">Entrar para cadastrar livros</h1>
                <p class="text-secondary mb-4">Use o e-mail e a senha da sua conta de funcionário.</p>
                @unless ($existeFuncionario)
                    <div class="alert alert-warning" role="status">
                        Ainda não há funcionário cadastrado. No terminal do projeto, execute
                        <code>php artisan biblioteca:criar-funcionario-inicial</code> e informe o nome, e-mail e senha.
                    </div>
                @endunless
                <form method="POST" action="{{ route('funcionarios.login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="username" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-4">
                        <input id="remember" name="remember" type="checkbox" value="1" class="form-check-input">
                        <label for="remember" class="form-check-label">Manter conectado</label>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Entrar</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
