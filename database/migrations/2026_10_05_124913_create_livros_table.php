<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livros', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('titulo');
            $table->string('autor');
            $table->string('editora')->nullable();
            $table->unsignedSmallInteger('ano_publicacao')->nullable();
            $table->unsignedSmallInteger('paginas')->nullable();
            $table->string('isbn')->nullable();
            $table->text('sinopse');
            $table->string('categoria');
            $table->json('assuntos')->nullable();
            $table->string('classificacao')->nullable();
            $table->string('localizacao');
            $table->unsignedSmallInteger('exemplares_totais')->default(1);
            $table->unsignedSmallInteger('exemplares_emprestados')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};
