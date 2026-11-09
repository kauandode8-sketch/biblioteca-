
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

    if ($request->has('token')&& $request->has('usuario_id')) { 
        $token = usuario_token::where('usuario_id', $request->input('usuario_id'))
            ->where('token', $request->input('token'))
            ->where('valido_ate', '>', now())
            ->first();
            if (!$token) {
                return next($request);
            }
        return $next($request);
    }else{
        return response()->json({'message' => 'Token não fornecido'})
        'erro' => 's'], 200);
    }
}
