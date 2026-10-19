<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('livro_id')->constrained('livros')->cascadeOnDelete();
            $table->string('nome');
            $table->string('email');
            $table->string('codigo', 8)->unique();
            $table->string('status')->default('reservada');
            $table->timestamp('expira_em');
            $table->timestamps();
            $table->unique(['livro_id', 'email']);
            $table->index(['livro_id', 'status', 'expira_em']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
