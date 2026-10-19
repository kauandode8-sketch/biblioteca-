<?php

namespace Database\Seeders;

use App\Models\Livro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LivroSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Livro::firstOrCreate(
            ['slug' => 'ideias-para-adiar-o-fim-do-mundo'],
            [
                'titulo' => 'Ideias para adiar o fim do mundo',
                'autor' => 'Ailton Krenak',
                'editora' => 'Companhia das Letras',
                'ano_publicacao' => 2019,
                'paginas' => 104,
                'isbn' => '978-85-359-3241-8',
                'sinopse' => 'Ailton Krenak questiona a ideia de humanidade como algo separado da natureza e convida o leitor a suspender o céu, ampliar os horizontes e enriquecer nossas subjetividades. Em um ensaio breve e contundente, propõe novas formas de habitar o planeta e de sonhar coletivamente.',
                'categoria' => 'Ensaio',
                'assuntos' => ['Povos indígenas', 'Ecologia', 'Filosofia', 'Brasil', 'Antropologia'],
                'classificacao' => '305.8 K92',
                'localizacao' => 'Biblioteca Central · 2º andar',
                'exemplares_totais' => 5,
                'exemplares_emprestados' => 2,
            ],
        );

        $livros = [
            [
                'slug' => 'torto-arado',
                'titulo' => 'Torto Arado',
                'autor' => 'Itamar Vieira Junior',
                'ano_publicacao' => 2019,
                'sinopse' => "As irm\u{00E3}s Bibiana e Belon\u{00ED}sia vivem na Fazenda \u{00C1}gua Negra, no sert\u{00E3}o da Bahia. A hist\u{00F3}ria acompanha suas trajet\u{00F3}rias e as rela\u{00E7}\u{00F5}es de trabalho, fam\u{00ED}lia e mem\u{00F3}ria que atravessam gera\u{00E7}\u{00F5}es.",
                'categoria' => 'Literatura brasileira',
                'assuntos' => ['Brasil', "Fam\u{00ED}lia", "Mem\u{00F3}ria", "Sert\u{00E3}o"],
                'classificacao' => '869.3 V658',
                'localizacao' => "Biblioteca Central \u{00B7} 2\u{00BA} andar",
                'exemplares_totais' => 4,
                'exemplares_emprestados' => 1,
            ],
            [
                'slug' => 'o-pequeno-principe',
                'titulo' => "O Pequeno Pr\u{00ED}ncipe",
                'autor' => "Antoine de Saint-Exup\u{00E9}ry",
                'ano_publicacao' => 1943,
                'sinopse' => "Ap\u{00F3}s encontrar um pequeno pr\u{00ED}ncipe vindo de outro planeta, um aviador passa a conhecer suas viagens e reflex\u{00F5}es sobre amizade, afeto e responsabilidade.",
                'categoria' => "Cl\u{00E1}ssicos",
                'assuntos' => ['Amizade', "Inf\u{00E2}ncia", 'Filosofia', 'Fantasia'],
                'classificacao' => '843 S137',
                'localizacao' => "Biblioteca Central \u{00B7} 1\u{00BA} andar",
                'exemplares_totais' => 4,
                'exemplares_emprestados' => 1,
            ],
            [
                'slug' => 'cem-anos-de-solidao',
                'titulo' => "Cem anos de solid\u{00E3}o",
                'autor' => "Gabriel Garc\u{00ED}a M\u{00E1}rquez",
                'ano_publicacao' => 1967,
                'sinopse' => "A saga da fam\u{00ED}lia Buend\u{00ED}a se desenrola ao longo de v\u{00E1}rias gera\u{00E7}\u{00F5}es na cidade fict\u{00ED}cia de Macondo, entre acontecimentos cotidianos, hist\u{00F3}ricos e fant\u{00E1}sticos.",
                'categoria' => "Realismo m\u{00E1}gico",
                'assuntos' => ["Am\u{00E9}rica Latina", "Fam\u{00ED}lia", "Mem\u{00F3}ria", "Realismo m\u{00E1}gico"],
                'classificacao' => '863 G216',
                'localizacao' => "Biblioteca Central \u{00B7} 2\u{00BA} andar",
                'exemplares_totais' => 4,
                'exemplares_emprestados' => 1,
            ],
            [
                'slug' => 'a-hora-da-estrela',
                'titulo' => 'A hora da estrela',
                'autor' => 'Clarice Lispector',
                'ano_publicacao' => 1977,
                'sinopse' => "A vida de Macab\u{00E9}a, uma jovem nordestina que vive no Rio de Janeiro, \u{00E9} narrada por Rodrigo S. M. em uma hist\u{00F3}ria sobre invisibilidade, desejo e exist\u{00EA}ncia.",
                'categoria' => 'Literatura brasileira',
                'assuntos' => ['Brasil', 'Identidade', 'Desigualdade', "Exist\u{00EA}ncia"],
                'classificacao' => '869.3 L767',
                'localizacao' => "Biblioteca Central \u{00B7} 2\u{00BA} andar",
                'exemplares_totais' => 4,
                'exemplares_emprestados' => 1,
            ],
            [
                'slug' => 'sapiens',
                'titulo' => "Sapiens: uma breve hist\u{00F3}ria da humanidade",
                'autor' => 'Yuval Noah Harari',
                'ano_publicacao' => 2011,
                'sinopse' => "O livro percorre a hist\u{00F3}ria da humanidade e discute como mudan\u{00E7}as culturais, sociais e tecnol\u{00F3}gicas moldaram a esp\u{00E9}cie humana.",
                'categoria' => "Hist\u{00F3}ria",
                'assuntos' => ["Hist\u{00F3}ria", 'Humanidade', 'Sociedade', "Ci\u{00EA}ncia"],
                'classificacao' => '909 H254',
                'localizacao' => "Biblioteca Central \u{00B7} 3\u{00BA} andar",
                'exemplares_totais' => 4,
                'exemplares_emprestados' => 1,
            ],
        ];

        foreach ($livros as $livro) {
            Livro::updateOrCreate(
                ['slug' => $livro['slug']],
                $livro,
            );
        }
    }
}
