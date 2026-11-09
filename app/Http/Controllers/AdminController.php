<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\AdminToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'Email ou senha incorretos!',
                'erro' => 's'
            ], 401);
        }

        $token = Str::random(60);

        AdminToken::create([
            'admin_id' => $admin->id,
            'token_admin' => Hash::make($token),
            'valido_ate' => Carbon::now()->addHours(24)
        ]);

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'admin_id' => $admin->id,
            'token_admin' => $token
        ], 200);
    }

    public function logout(Request $request)
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

            $token->delete();

            return response()->json([
                'message' => 'Logout realizado com sucesso!',
                'erro' => 'n'
            ], 200);
        }

        return response()->json([
            'message' => 'Informe o ID e o token!',
            'erro' => 's'
        ], 400);
    }
}