<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->renameColumn('data_nacimento', 'data_nascimento');
        });

        Schema::table('usuario', function (Blueprint $table) {
            $table->string('password')->nullable()->after('cpf');
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->dropColumn(['password', 'remember_token']);
        });

        Schema::table('usuario', function (Blueprint $table) {
            $table->renameColumn('data_nascimento', 'data_nacimento');
        });
    }
};
