<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use illuminate\support\Facades\Hash;

class FuncionarioLoginController
{
    public function salvar_funcionario(Request $request): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:funcionarios,email',
            'senha' => 'required|string|min:6',
        ]);

        try {
            $funcionario = new Funcionario;
            $funcionario->nome = $request->nome;
            $funcionario->email = $request->email;
            $funcionario->senha = Hash::make($request->senha);

            $funcionario->save();

        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->withErrors(['erro' => 'Erro ao cadastrar funcionário!', 'msg_erro' => $th->getMessage()]);
        }

        return redirect()->route('funcionarios.login')->with('success', 'Funcionário cadastrado com sucesso!');
    }

    public function listar_funcionario(Request $request)
    {
        $funcionario = Funcionario::all();

        return response()->json(['funcionarios' => $funcionario], 200);
    }

    public function alterar_funcionario(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:funcionarios,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:funcionarios,email,'.$request->id,
            'password' => 'required|string|min:6',

        ]);

        try {

            $funcionario = Funcionario::find($request->id);

            $funcionario->name = $request->name;
            $funcionario->email = $request->email;
            $funcionario->password = $request->password;
            $funcionario->save();

            return response()->json(['message' => 'Funcionário cadastrado com sucesso!', 'erro' => 'n'], 200);

        } catch (\throwable $th) {
            return response()->json(['message' => 'Erro ao cadastrar funcionário: ', 'erro' => 's', 'msg_erro' => $th->getMessage(), 'erro' => 's'], 200);
        }

    }

    public function deletar_funcionario(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:funcionarios,id',
        ]);

        try {
            $funcionario = Funcionario::find($request->id);
            $funcionario->delete();

            return response()->json(['message' => 'Funcionário deletado com sucesso!', 'erro' => 'n'], 200);
        } catch (\throwable $th) {
            return response()->json(['message' => 'Erro ao deletar funcionário: ', 'erro' => 's', 'msg_erro' => $th->getMessage(), 'erro' => 's'], 200);
        }
    }
}
