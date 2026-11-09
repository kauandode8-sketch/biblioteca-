<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AdminToken;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('admin_id') && $request->has('token_admin')) {

            $token = AdminToken::where('admin_id', $request->admin_id)
                ->where('valido_ate', '>', Carbon::now())
                ->first();

            if (!$token || !Hash::check($request->token_admin, $token->token_admin)) {
                return response()->json([
                    'message' => 'Token inválido ou expirado!',
                    'erro' => 's'
                ], 401);
            }

            return $next($request);
        }

        return response()->json([
            'message' => 'Informe o ID e o token do administrador!',
            'erro' => 's'
        ], 401);
    }
}