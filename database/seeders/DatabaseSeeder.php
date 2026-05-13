<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@bookhub.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Regular users
        $alice = User::factory()->create([
            'name'     => 'Alice',
            'email'    => 'alice@bookhub.com',
            'password' => Hash::make('password'),
        ]);
        $bob = User::factory()->create([
            'name'     => 'Bob',
            'email'    => 'bob@bookhub.com',
            'password' => Hash::make('password'),
        ]);

        // Genres
        $genres = collect([
            'Novela', 'Ciencia ficción', 'Fantasía', 'Terror',
            'Misterio', 'Romance', 'Histórica', 'Ensayo', 'Clásico',
        ])->map(fn($name) => Genre::create(['name' => $name]));

        // Sample books
        $books = [
            ['title' => 'Cien años de soledad',  'author' => 'Gabriel García Márquez', 'year' => 1967, 'rating' => 9.5, 'description' => 'La saga de la familia Buendía a lo largo de siete generaciones en el pueblo ficticio de Macondo.'],
            ['title' => 'El señor de los anillos', 'author' => 'J.R.R. Tolkien',         'year' => 1954, 'rating' => 9.4, 'description' => 'La épica aventura de Frodo Bolsón para destruir el Anillo Único.'],
            ['title' => 'Dune',                   'author' => 'Frank Herbert',           'year' => 1965, 'rating' => 9.2, 'description' => 'La historia de Paul Atreides en el planeta desértico Arrakis.'],
            ['title' => '1984',                   'author' => 'George Orwell',           'year' => 1949, 'rating' => 9.0, 'description' => 'Una distopía sobre el totalitarismo y la vigilancia perpetua.'],
            ['title' => 'El nombre de la rosa',   'author' => 'Umberto Eco',             'year' => 1980, 'rating' => 8.7, 'description' => 'Un monje franciscano investiga una serie de muertes en una abadía medieval.'],
            ['title' => 'Don Quijote de la Mancha', 'author' => 'Miguel de Cervantes',  'year' => 1605, 'rating' => 8.5, 'description' => 'Las aventuras del caballero andante Alonso Quijano y su escudero Sancho Panza.'],
        ];

        $genreMap = $genres->keyBy('name');

        $bookGenreMap = [
            'Cien años de soledad'      => ['Novela', 'Clásico'],
            'El señor de los anillos'   => ['Fantasía', 'Clásico'],
            'Dune'                      => ['Ciencia ficción'],
            '1984'                      => ['Ciencia ficción', 'Clásico'],
            'El nombre de la rosa'      => ['Misterio', 'Histórica'],
            'Don Quijote de la Mancha'  => ['Novela', 'Clásico'],
        ];

        $createdBooks = [];
        foreach ($books as $data) {
            $book = Book::create($data);
            $genreIds = collect($bookGenreMap[$data['title']] ?? [])
                ->map(fn($g) => $genreMap[$g]->id ?? null)
                ->filter()
                ->values()
                ->all();
            $book->genres()->sync($genreIds);
            $createdBooks[$data['title']] = $book;
        }

        // Alice ha leído 3 libros y reseñado 2
        $aliceReads = ['Cien años de soledad', '1984', 'Dune'];
        foreach ($aliceReads as $title) {
            $alice->readBooks()->attach($createdBooks[$title]->id, ['read_at' => now()]);
        }

        $r1 = Review::create([
            'book_id' => $createdBooks['Cien años de soledad']->id,
            'user_id' => $alice->id,
            'rating'  => 10,
            'body'    => 'Una obra maestra del realismo mágico. Me dejó sin palabras.',
        ]);
        $r2 = Review::create([
            'book_id' => $createdBooks['Dune']->id,
            'user_id' => $alice->id,
            'rating'  => 9,
            'body'    => 'Worldbuilding espectacular, aunque denso al principio.',
        ]);

        // Bob también ha leído algunos
        $bobReads = ['Dune', '1984'];
        foreach ($bobReads as $title) {
            $bob->readBooks()->attach($createdBooks[$title]->id, ['read_at' => now()]);
        }

        $r3 = Review::create([
            'book_id' => $createdBooks['1984']->id,
            'user_id' => $bob->id,
            'rating'  => 9,
            'body'    => 'Vigente más que nunca. Lectura obligatoria.',
        ]);

        // Comentarios en el hilo de Alice sobre Dune
        Comment::create([
            'review_id' => $r2->id,
            'user_id'   => $bob->id,
            'body'      => 'Coincido, los primeros capítulos cuestan pero merece la pena.',
        ]);
        Comment::create([
            'review_id' => $r2->id,
            'user_id'   => $alice->id,
            'body'      => '¡Exacto! ¿Has leído también la segunda parte?',
        ]);
    }
}
