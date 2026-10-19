<?php

use App\Http\Controllers\Api\FuncionarioController;
use App\Http\Controllers\Api\LivroController as ApiLivroController;
use App\Http\Controllers\Api\ReservaController as ApiReservaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/usuario_cadastro', [UsuarioController::class, 'store'])->middleware('throttle:5,1');
Route::post('/usuarios', [UsuarioController::class, 'store'])->middleware('throttle:5,1')->name('api.usuarios.store');
Route::post('/login', [UsuarioController::class, 'login'])->middleware('throttle:10,1');
Route::post('/funcionarios/login', [FuncionarioController::class, 'login'])->middleware('throttle:10,1');

Route::get('/livros', [ApiLivroController::class, 'index'])->name('api.livros.index');
Route::get('/livros/{livro}', [ApiLivroController::class, 'show'])->name('api.livros.show');

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function (): void {
    Route::apiResource('usuarios', UsuarioController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::apiResource('funcionarios', FuncionarioController::class);
    Route::apiResource('reservas', ApiReservaController::class);
    Route::apiResource('livros', ApiLivroController::class)->only(['store', 'update', 'destroy']);

    Route::delete('/tokens/current', function (Request $request) {
        $token = $request->user()->currentAccessToken();
        abort_unless($token instanceof PersonalAccessToken, 400, 'Esta sessão não possui token pessoal para revogar.');
        $token->delete();

        return response()->json(['message' => 'Token revogado com sucesso.']);
    })->name('api.tokens.destroy');
});
