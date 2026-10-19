<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de usuário | Biblioteca</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #183a32;
            --muted: #6d7d76;
            --line: #dce5df;
            --paper: #ffffff;
            --canvas: #f2f5ef;
            --green: #286653;
            --green-dark: #17483a;
            --lime: #d9e9b8;
            --coral: #d77b5b;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 32px;
            color: var(--ink);
            background-color: var(--canvas);
            background-image: radial-gradient(#d7e0d6 0.7px, transparent 0.7px);
            background-size: 18px 18px;
            font-family: "Segoe UI", sans-serif;
        }

        .page {
            width: min(100%, 1080px);
            min-height: 690px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 0.82fr 1.18fr;
            overflow: hidden;
            border: 1px solid #e1e8df;
            border-radius: 10px;
            background: var(--paper);
            box-shadow: 0 24px 70px #24443416;
            animation: arrive 500ms ease-out both;
        }

        .intro {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 42px;
            color: #f7f7ef;
            background-color: var(--green-dark);
            background-image: linear-gradient(145deg, #1b5141, #153c34 72%);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 15px;
            font-weight: 700;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border: 1px solid #ffffff55;
            border-radius: 8px;
            color: var(--lime);
            font-family: Georgia, serif;
            font-size: 22px;
        }

        .intro-copy { margin: 58px 0; }

        .eyebrow {
            margin: 0 0 18px;
            color: var(--lime);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.6px;
            text-transform: uppercase;
        }

        h1 {
            max-width: 350px;
            margin: 0;
            font-family: Georgia, serif;
            font-size: clamp(36px, 4vw, 54px);
            font-weight: 400;
            line-height: 1.06;
        }

        .intro-copy p:last-child {
            max-width: 310px;
            margin: 20px 0 0;
            color: #d2dfd7;
            font-size: 15px;
            line-height: 1.7;
        }

        .intro-note {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #d2dfd7;
            font-size: 12px;
        }

        .intro-note::before {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--coral);
            content: "";
        }

        .form-panel { padding: 54px clamp(28px, 6vw, 72px); }

        .form-heading { margin-bottom: 32px; }

        .form-heading p {
            margin: 0 0 8px;
            color: var(--green);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        h2 {
            margin: 0;
            color: var(--ink);
            font-family: Georgia, serif;
            font-size: 32px;
            font-weight: 400;
        }

        .form-heading span {
            display: block;
            margin-top: 9px;
            color: var(--muted);
            font-size: 14px;
        }

        .fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 16px;
        }

        .field { min-width: 0; }
        .field-wide { grid-column: 1 / -1; }

        label {
            display: block;
            margin-bottom: 8px;
            color: #314c43;
            font-size: 13px;
            font-weight: 650;
        }

        input {
            width: 100%;
            height: 46px;
            padding: 0 13px;
            border: 1px solid var(--line);
            border-radius: 5px;
            outline: none;
            background: #fcfdfb;
            color: var(--ink);
            font: inherit;
            font-size: 14px;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }

        input::placeholder { color: #9aa8a0; }
        input:focus { border-color: var(--green); box-shadow: 0 0 0 3px #28665319; }

        .submit-button {
            width: 100%;
            min-height: 48px;
            margin-top: 28px;
            border: 0;
            border-radius: 5px;
            background: var(--green);
            color: white;
            cursor: pointer;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            transition: background-color 150ms ease, transform 150ms ease;
        }

        .submit-button:hover { background: #1d5745; transform: translateY(-1px); }
        .back-link { display: block; margin-top: 18px; color: var(--muted); font-size: 13px; text-align: center; text-decoration: none; }
        .back-link:hover { color: var(--green); }

        @keyframes arrive {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 760px) {
            body { padding: 16px; }
            .page { min-height: 0; grid-template-columns: 1fr; }
            .intro { min-height: 245px; padding: 26px; }
            .intro-copy { margin: 34px 0 25px; }
            .intro-copy p:last-child { margin-top: 12px; }
            .intro-note { display: none; }
            .form-panel { padding: 34px 26px; }
        }

        @media (max-width: 460px) {
            body { padding: 0; background: var(--paper); }
            .page { border: 0; border-radius: 0; box-shadow: none; }
            .intro { min-height: 215px; }
            .fields { grid-template-columns: 1fr; gap: 17px; }
            .field-wide { grid-column: auto; }
            h1 { font-size: 39px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
        }
    </style>
    <link href="{{ asset('sesi-theme.css') }}" rel="stylesheet">
</head>
<body class="sesi-registration">
    <main class="page">
        <section class="intro" aria-label="Biblioteca">
            <div class="brand"><span class="brand-mark" aria-hidden="true">B</span> Biblioteca</div>
            <div class="intro-copy">
                <p class="eyebrow">Um lugar para boas histórias</p>
                <h1>Faça parte da nossa comunidade.</h1>
                <p>Crie seu cadastro para acompanhar suas leituras e aproveitar a biblioteca.</p>
            </div>
            <div class="intro-note">Seu próximo capítulo começa aqui</div>
        </section>

        <section class="form-panel" aria-labelledby="form-title">
            <header class="form-heading">
                <p>Novo cadastro</p>
                <h2 id="form-title">Seus dados</h2>
                <span>Preencha as informações para criar seu perfil.</span>
            </header>

            <form method="POST" action="{{ route('usuarios.store') }}">
                @csrf
                <div class="fields">
                    <div class="field field-wide">
                        <label for="nome">Nome completo</label>
                        <input type="text" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Como podemos chamar você?" autocomplete="name" required>
                        @error('nome')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="field field-wide">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="voce@exemplo.com" autocomplete="email" required>
                        @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="cpf">CPF</label>
                        <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" placeholder="Somente números" inputmode="numeric" maxlength="11" pattern="[0-9]{11}" title="Digite os 11 números do CPF" required>
                        @error('cpf')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="data_nascimento">Data de nascimento</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}" autocomplete="bday" required>
                        @error('data_nascimento')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Mínimo de 8 caracteres" autocomplete="new-password" minlength="8" required>
                        @error('senha')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="senha_confirmacao">Confirme a senha</label>
                        <input type="password" id="senha_confirmation" name="senha_confirmation" placeholder="Digite a senha novamente" autocomplete="new-password" minlength="8" required>
                    </div>
                </div>
                <button class="submit-button" type="submit">Cadastrar usuário</button>
            </form>

            <a class="back-link" href="{{ route('home') }}">Voltar ao início</a>
        </section>
    </main>
</body>
</html>
