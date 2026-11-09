<?php

use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\FuncionarioLoginController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LivroController::class, 'index'])->name('home');
Route::view('/login', 'login')->name('login');
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
Route::get('/funcionario/login', [FuncionarioLoginController::class, 'create'])->name('funcionarios.login');
Route::post('/funcionario/login', [FuncionarioLoginController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('funcionarios.login.store');
Route::post('/funcionario/logout', [FuncionarioLoginController::class, 'destroy'])
    ->middleware('auth:funcionarios')
    ->name('funcionarios.logout');
Route::get('/biblioteca', [LivroController::class, 'index'])->name('biblioteca.inicio');
Route::get('/emprestimos', [EmprestimoController::class, 'index'])
    ->middleware('auth')
    ->name('emprestimos.index');
Route::view('/cadastro-usuario', 'cadastro_usuario')->name('usuarios.create');
Route::post('/cadastro-usuario', [UsuarioController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('usuarios.store');

Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
Route::middleware('auth:funcionarios')->group(function (): void {
    Route::get('/funcionario/livros/cadastrar', [LivroController::class, 'create'])->name('funcionario.livros.create');
    Route::post('/funcionario/livros', [LivroController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('funcionario.livros.store');
    Route::get('/funcionario/livros/{livro:slug}/editar', [LivroController::class, 'edit'])->name('funcionario.livros.edit');
    Route::put('/funcionario/livros/{livro:slug}', [LivroController::class, 'update'])->name('funcionario.livros.update');
    Route::delete('/funcionario/livros/{livro:slug}', [LivroController::class, 'destroy'])->name('funcionario.livros.destroy');
});
Route::get('/livros/{livro:slug}', [LivroController::class, 'show'])->name('livros.show');
Route::post('/livros/{livro:slug}/reservas', [ReservaController::class, 'store'])
    ->middleware(['auth', 'throttle:10,1'])
    ->name('reservas.store');

Route::get('/login_teste', [LoginController22::class, 'login_teste'])->name('login');
