<?php

namespace App\Console\Commands;

use App\Models\Funcionario;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('biblioteca:criar-funcionario-inicial')]
#[Description('Cria o primeiro funcionário administrador da API da biblioteca.')]
class CriarFuncionarioInicial extends Command
{
    public function handle(): int
    {
        if (Funcionario::query()->exists()) {
            $this->error('Já existe um funcionário. Cadastre os próximos pela API autenticada.');

            return self::FAILURE;
        }

        $name = trim((string) $this->ask('Nome do funcionário'));
        $email = Str::lower(trim((string) $this->ask('E-mail')));

        if ($name === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->error('Informe um nome e um e-mail válido.');

            return self::FAILURE;
        }

        if (Funcionario::query()->where('email', $email)->exists()) {
            $this->error('Este e-mail já está cadastrado.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Senha (mínimo de 8 caracteres)');
        $passwordConfirmation = (string) $this->secret('Confirme a senha');

        if (mb_strlen($password) < 8 || $password !== $passwordConfirmation) {
            $this->error('As senhas precisam ter pelo menos 8 caracteres e ser iguais.');

            return self::FAILURE;
        }

        Funcionario::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $this->info('Funcionário inicial criado. Use o endpoint POST /api/funcionarios/login para gerar um token.');

        return self::SUCCESS;
    }
}
