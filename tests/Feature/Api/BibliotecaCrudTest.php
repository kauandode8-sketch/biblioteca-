<?php

namespace Tests\Feature\Api;

use App\Models\Funcionario;
use App\Models\Usuario as UsuarioAutenticado;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BibliotecaCrudTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.biblioteca' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);
        DB::purge('biblioteca');

        Schema::connection('biblioteca')->create('usuario', function (Blueprint $table): void {
            $table->increments('id_usuario');
            $table->string('nome', 100);
            $table->string('cpf', 14)->unique();
            $table->string('email', 100)->nullable()->unique();
            $table->string('telefone', 20)->nullable();
            $table->string('tipo_usuario', 30);
        });

        Schema::connection('biblioteca')->create('categoria', function (Blueprint $table): void {
            $table->increments('id_categoria');
            $table->string('nome', 100);
            $table->string('descricao', 255)->nullable();
        });

        Schema::connection('biblioteca')->create('livro', function (Blueprint $table): void {
            $table->increments('id_livro');
            $table->unsignedInteger('id_categoria');
            $table->string('titulo', 150);
            $table->string('autor', 100);
            $table->string('isbn', 20)->nullable()->unique();
            $table->unsignedSmallInteger('ano_publicacao')->nullable();
            $table->unsignedInteger('quantidade')->default(1);
            $table->unsignedInteger('disponivel')->default(1);
            $table->foreign('id_categoria')->references('id_categoria')->on('categoria');
        });
    }

    public function test_staff_can_create_update_and_delete_a_legacy_user(): void
    {
        $this->authenticateStaff();

        $payload = [
            'nome' => '  Ana   Silva  ',
            'cpf' => '123.456.789-00',
            'email' => 'ANA@EXAMPLE.COM',
            'telefone' => '11999999999',
            'tipo_usuario' => 'aluno',
        ];

        $created = $this->postJson('/api/biblioteca/usuarios', $payload)
            ->assertCreated()
            ->assertJsonPath('data.nome', 'Ana Silva')
            ->assertJsonPath('data.email', 'ana@example.com');
        $id = $created->json('data.id_usuario');

        $this->assertDatabaseHas('usuario', ['id_usuario' => $id, 'cpf' => '123.456.789-00'], 'biblioteca');
        $this->getJson('/api/biblioteca/usuarios')->assertOk()->assertJsonPath('data.0.id_usuario', $id);
        $this->getJson("/api/biblioteca/usuarios/{$id}")->assertOk()->assertJsonPath('data.tipo_usuario', 'aluno');
        $this->patchJson("/api/biblioteca/usuarios/{$id}", ['nome' => 'Ana Souza'])
            ->assertOk()
            ->assertJsonPath('data.nome', 'Ana Souza');
        $this->deleteJson("/api/biblioteca/usuarios/{$id}")->assertOk();

        $this->assertDatabaseMissing('usuario', ['id_usuario' => $id], 'biblioteca');
    }

    public function test_staff_can_manage_categories_and_books_with_their_category_relation(): void
    {
        $this->authenticateStaff();

        $category = $this->postJson('/api/biblioteca/categorias', [
            'nome' => 'Literatura',
            'descricao' => 'Romances e contos',
        ])->assertCreated()->json('data.id_categoria');

        $this->patchJson("/api/biblioteca/categorias/{$category}", ['nome' => 'Literatura brasileira'])
            ->assertOk()
            ->assertJsonPath('data.nome', 'Literatura brasileira');
        $this->getJson('/api/biblioteca/categorias')->assertOk()->assertJsonPath('data.0.id_categoria', $category);
        $this->getJson("/api/biblioteca/categorias/{$category}")
            ->assertOk()
            ->assertJsonPath('data.nome', 'Literatura brasileira');

        $book = $this->postJson('/api/biblioteca/livros', [
            'id_categoria' => $category,
            'titulo' => 'Vidas Secas',
            'autor' => 'Graciliano Ramos',
            'isbn' => '9780000000001',
            'ano_publicacao' => 1938,
            'quantidade' => 4,
            'disponivel' => 3,
        ])->assertCreated()
            ->assertJsonPath('data.categoria.nome', 'Literatura brasileira')
            ->json('data.id_livro');

        $this->assertDatabaseHas('livro', ['id_livro' => $book, 'id_categoria' => $category], 'biblioteca');
        $this->getJson("/api/biblioteca/livros?id_categoria={$category}")
            ->assertOk()
            ->assertJsonPath('data.0.id_livro', $book)
            ->assertJsonPath('data.0.categoria.id_categoria', $category);
        $this->getJson("/api/biblioteca/livros/{$book}")->assertOk()->assertJsonPath('data.titulo', 'Vidas Secas');
        $this->patchJson("/api/biblioteca/livros/{$book}", ['disponivel' => 2])
            ->assertOk()
            ->assertJsonPath('data.disponivel', 2);
        $this->deleteJson("/api/biblioteca/livros/{$book}")->assertOk();
        $this->deleteJson("/api/biblioteca/categorias/{$category}")->assertOk();

        $this->assertDatabaseMissing('livro', ['id_livro' => $book], 'biblioteca');
        $this->assertDatabaseMissing('categoria', ['id_categoria' => $category], 'biblioteca');
    }

    public function test_category_cannot_be_deleted_while_books_use_it(): void
    {
        $this->authenticateStaff();
        $category = $this->postJson('/api/biblioteca/categorias', ['nome' => 'História'])
            ->assertCreated()
            ->json('data.id_categoria');
        $this->postJson('/api/biblioteca/livros', [
            'id_categoria' => $category,
            'titulo' => 'História do Brasil',
            'autor' => 'Autoria Teste',
            'quantidade' => 1,
            'disponivel' => 1,
        ])->assertCreated();

        $this->deleteJson("/api/biblioteca/categorias/{$category}")
            ->assertStatus(409)
            ->assertJsonPath('message', 'Não é possível excluir uma categoria associada a livros.');

        $this->assertDatabaseHas('categoria', ['id_categoria' => $category], 'biblioteca');
    }

    public function test_book_creation_rejects_a_category_that_does_not_exist(): void
    {
        $this->authenticateStaff();

        $this->postJson('/api/biblioteca/livros', [
            'id_categoria' => 999,
            'titulo' => 'Livro sem categoria',
            'autor' => 'Autor Teste',
            'quantidade' => 1,
            'disponivel' => 1,
        ])->assertUnprocessable()->assertJsonValidationErrors('id_categoria');

        $this->assertDatabaseCount('livro', 0, 'biblioteca');
    }

    public function test_unauthenticated_requests_cannot_manage_the_sql_crud(): void
    {
        $this->postJson('/api/biblioteca/categorias', ['nome' => 'Literatura'])->assertUnauthorized();
        $this->getJson('/api/biblioteca/usuarios')->assertUnauthorized();
    }

    public function test_regular_library_users_cannot_create_categories_or_books(): void
    {
        Sanctum::actingAs(new UsuarioAutenticado, ['*']);

        $this->postJson('/api/biblioteca/categorias', ['nome' => 'Literatura'])->assertForbidden();
        $this->postJson('/api/biblioteca/livros', [])->assertForbidden();

        $this->assertDatabaseCount('categoria', 0, 'biblioteca');
        $this->assertDatabaseCount('livro', 0, 'biblioteca');
    }

    private function authenticateStaff(): void
    {
        Sanctum::actingAs(new Funcionario, ['*']);
    }
}
