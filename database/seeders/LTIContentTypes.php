<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LTIContentType;

class LTIContentTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // level 1
        LTIContentType::updateOrInsert( [ 'name' => 'Textbooks', 'language' => 'en' ], [ 'level' => (int) 1, 'language' => 'en' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Libros', 'language' => 'sp' ], [ 'level' => (int) 1, 'language' => 'sp' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Livros', 'language' => 'pt' ], [ 'level' => (int) 1, 'language' => 'pt' ] );

        //level 2
        LTIContentType::updateOrInsert( [ 'name' => 'Book Chapter', 'language' => 'en' ], [ 'level' => (int) 2, 'language' => 'en' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Capítulo', 'language' => 'sp' ], [ 'level' => (int) 2, 'language' => 'sp' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Capítulo de Livro', 'language' => 'pt' ], [ 'level' => (int) 2, 'language' => 'pt' ] );

        // level 3
        LTIContentType::updateOrInsert( [ 'name' => 'Quick Reference Resources', 'language' => 'en' ], [ 'level' => (int) 3, 'language' => 'en' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Referencia rápida', 'language' => 'sp' ], [ 'level' => (int) 3, 'language' => 'sp' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Referência rápida', 'language' => 'pt' ], [ 'level' => (int) 3, 'language' => 'pt' ] );

        LTIContentType::updateOrInsert( [ 'name' => 'Patient Education', 'language' => 'en' ], [ 'level' => (int) 3, 'language' => 'en' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Patient Education', 'language' => 'sp' ], [ 'level' => (int) 3, 'language' => 'sp' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Educação do Paciente', 'language' => 'pt' ], [ 'level' => (int) 3, 'language' => 'pt' ] );

        LTIContentType::updateOrInsert( [ 'name' => 'Multimedia', 'language' => 'en' ], [ 'level' => (int) 3, 'language' => 'en' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Multimedia', 'language' => 'sp' ], [ 'level' => (int) 3, 'language' => 'sp' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Multimídia', 'language' => 'pt' ], [ 'level' => (int) 3, 'language' => 'pt' ] );

        LTIContentType::updateOrInsert( [ 'name' => 'Case', 'language' => 'en' ], [ 'level' => (int) 3, 'language' => 'en' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Caso', 'language' => 'sp' ], [ 'level' => (int) 3, 'language' => 'sp' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Caso', 'language' => 'pt' ], [ 'level' => (int) 3, 'language' => 'pt' ] );

        LTIContentType::updateOrInsert( [ 'name' => 'Images', 'language' => 'en' ], [ 'level' => (int) 3, 'language' => 'en' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Images', 'language' => 'sp' ], [ 'level' => (int) 3, 'language' => 'sp' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Images', 'language' => 'pt' ], [ 'level' => (int) 3, 'language' => 'pt' ] );

        LTIContentType::updateOrInsert( [ 'name' => 'Tables', 'language' => 'en' ], [ 'level' => (int) 3, 'language' => 'en' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Cuadros', 'language' => 'sp' ], [ 'level' => (int) 3, 'language' => 'sp' ] );
        LTIContentType::updateOrInsert( [ 'name' => 'Tabelas', 'language' => 'pt' ], [ 'level' => (int) 3, 'language' => 'pt' ] );

    }
}
