<?php

use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'login')->name('home');
Route::view('/login', 'login')->name('login');
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
Route::get('/biblioteca', [LivroController::class, 'index'])->name('biblioteca.inicio');
Route::get('/emprestimos', [EmprestimoController::class, 'index'])
    ->middleware('auth')
    ->name('emprestimos.index');
Route::view('/cadastro-usuario', 'cadastro_usuario')->name('usuarios.create');
Route::post('/cadastro-usuario', [UsuarioController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('usuarios.store');

Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
Route::get('/livros/{livro:slug}', [LivroController::class, 'show'])->name('livros.show');
Route::post('/livros/{livro:slug}/reservas', [ReservaController::class, 'store'])
    ->middleware(['auth', 'throttle:10,1'])
    ->name('reservas.store');
