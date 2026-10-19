<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmprestimoController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('email_usuario');

        if (! is_string($email) || $email === '') {
            return to_route('login')->with('status', 'Entre com seu e-mail para consultar suas reservas.');
        }

        $reservas = Reserva::query()
            ->with('livro')
            ->where('email', $email)
            ->where('status', 'reservada')
            ->where('expira_em', '>', now())
            ->latest()
            ->get();

        return view('emprestimos', compact('reservas'));
    }
}
