<?php

use App\Http\Controllers\Api\Biblioteca\CategoriaController as BibliotecaCategoriaController;
use App\Http\Controllers\Api\Biblioteca\LivroController as BibliotecaLivroController;
use App\Http\Controllers\Api\Biblioteca\UsuarioController as BibliotecaUsuarioController;
use App\Http\Controllers\Api\FuncionarioController as ApiFuncionarioController;
use App\Http\Controllers\Api\LivroController as ApiLivroController;
use App\Http\Controllers\FuncionarioLoginController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

Route::post('/usuario_cadastro', [ApiUsuarioController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('api.usuarios.register');
Route::post('/usuarios', [UsuarioController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('api.usuarios.store');
Route::post('/login', [UsuarioController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('api.usuarios.login');
Route::post('/funcionarios/login', [ApiFuncionarioController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('api.funcionarios.login');
Route::post('/salvar_funcionario', [ApiFuncionarioController::class, 'salvar_funcionario'])
    ->middleware('throttle:5,1')
    ->name('api.funcionarios.save');

Route::get('/livros', [ApiLivroController::class, 'index'])->name('api.livros.index');
Route::get('/livros/{livro}', [ApiLivroController::class, 'show'])->name('api.livros.show');

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function (): void {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');

    Route::apiResource('usuarios', UsuarioController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::apiResource('funcionarios', ApiFuncionarioController::class);
    Route::apiResource('reservas', ApiReservaController::class);
    Route::apiResource('livros', ApiLivroController::class)->only(['store', 'update', 'destroy']);

    Route::post('/salvar_livro', [ApiLivroController::class, 'salvar_livro'])
        ->name('api.livros.save');
    Route::post('/salvar_reserva', [ApiReservaController::class, 'store'])
        ->name('api.reservas.save');

    Route::delete('/tokens/current', function (Request $request) {
        $token = $request->user()->currentAccessToken();
        abort_unless($token instanceof PersonalAccessToken, 400, 'Esta sessão não possui token pessoal para revogar.');
        $token->delete();

        return response()->json(['message' => 'Token revogado com sucesso.']);
    })->name('api.tokens.destroy');
});

Route::prefix('biblioteca')->name('api.biblioteca.')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function (): void {
    Route::apiResource('usuarios', BibliotecaUsuarioController::class);
    Route::apiResource('categorias', BibliotecaCategoriaController::class);
    Route::apiResource('livros', BibliotecaLivroController::class);

});

Route::get('/listar_funcionario', [FuncionarioLoginController::class, 'listar_funcionario']);
Route::put('/alterar_funcionario', [FuncionarioLoginController::class, 'alterar_funcionario']);
Route::delete('/deletar_funcionario', [FuncionarioLoginController::class, 'deletar_funcionario']);

// reservas//

Route::get('/listar_reserva', [ReservaController::class, 'listar_reserva']);
Route::put('/alterar_reserva', [ReservaController::class, 'alterar_reserva']);
Route::delete('/deletar_reserva', [ReservaController::class, 'deletar_reserva']);
Route::post('/salvar_reserva', [ReservaController::class, 'salvar_reserva']);

//Route:post('/login', [UsuarioController::class, 'loginApi']);