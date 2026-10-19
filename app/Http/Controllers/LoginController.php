<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'senha' => ['required', 'string'],
        ]);

        $autenticado = Auth::attempt([
            'email' => Str::lower($dados['email']),
            'password' => $dados['senha'],
        ], $request->boolean('lembrar'));

        if (! $autenticado) {
            return to_route('login')
                ->withErrors(['email' => 'E-mail ou senha inválidos.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->put('nome_usuario', Auth::user()->nome);
        $request->session()->put('email_usuario', Auth::user()->email);

        return to_route('biblioteca.inicio');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }
}
