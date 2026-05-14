<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use App\Services\OpenLibraryService;
use Illuminate\Database\Seeder;

class BooksCatalogSeeder extends Seeder
{
    private const BOOKS_PER_SUBJECT = 28;

    /**
     * Mapeo de géneros (en español, como están en BD) a "subjects" de Open Library.
     */
    private array $genreSubjectMap = [
        'Novela'          => 'fiction',
        'Ciencia ficción' => 'science_fiction',
        'Fantasía'        => 'fantasy',
        'Terror'          => 'horror',
        'Misterio'        => 'mystery',
        'Romance'         => 'romance',
        'Histórica'       => 'historical_fiction',
        'Ensayo'          => 'essays',
        'Clásico'         => 'classics',
    ];

    public function run(): void
    {
        $ol = new OpenLibraryService();

        // Asegurar que los géneros existen
        $genres = [];
        foreach (array_keys($this->genreSubjectMap) as $name) {
            $genres[$name] = Genre::firstOrCreate(['name' => $name]);
        }

        $created = 0;
        $skipped = 0;
        $failed  = 0;

        foreach ($this->genreSubjectMap as $genreName => $subject) {
            $this->command->info("→ Buscando libros del subject '{$subject}' ({$genreName})...");

            try {
                $books = $ol->getBySubject($subject, self::BOOKS_PER_SUBJECT);
            } catch (\Throwable $e) {
                $this->command->error("  Fallo subject '{$subject}': " . $e->getMessage());
                continue;
            }

            if (empty($books)) {
                $this->command->warn("  Sin resultados para '{$subject}'.");
                continue;
            }

            foreach ($books as $data) {
                if (empty($data['title'])) {
                    continue;
                }

                // Dedupe case-insensitive por título
                $existing = Book::whereRaw('LOWER(title) = ?', [mb_strtolower($data['title'])])->first();
                if ($existing) {
                    $existing->genres()->syncWithoutDetaching([$genres[$genreName]->id]);
                    $skipped++;
                    continue;
                }

                // Enriquecer con descripción real del work
                $description = null;
                if (!empty($data['key'])) {
                    try {
                        $work = $ol->getWork($data['key']);
                        $description = $work['description'] ?? null;
                        if ($description && mb_strlen($description) > 1000) {
                            $description = mb_substr($description, 0, 997) . '...';
                        }
                    } catch (\Throwable $e) {
                        // silencioso
                    }
                }

                try {
                    $book = Book::create([
                        'title'       => $data['title'],
                        'author'      => $data['author'],
                        'year'        => $data['year'],
                        'rating'      => round(mt_rand(65, 95) / 10, 1),
                        'description' => $description ?: "Una obra destacada del género {$genreName}.",
                        'cover_url'   => $data['cover_url'],
                        'published'   => true,
                    ]);
                    $book->genres()->sync([$genres[$genreName]->id]);
                    $created++;
                    $this->command->line("  + {$data['title']} — " . ($data['author'] ?? 'desconocido'));
                } catch (\Throwable $e) {
                    $failed++;
                    $this->command->error("  ! Error creando '{$data['title']}': " . $e->getMessage());
                }
            }
        }

        $this->command->info('');
        $this->command->info('=== Resumen ===');
        $this->command->info("Creados:   {$created}");
        $this->command->info("Duplicados:{$skipped}");
        $this->command->info("Errores:   {$failed}");
    }
}
