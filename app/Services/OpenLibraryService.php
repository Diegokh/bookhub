<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OpenLibraryService
{
    private const SEARCH_URL   = 'https://openlibrary.org/search.json';
    private const WORK_URL     = 'https://openlibrary.org/{key}.json';
    private const SUBJECT_URL  = 'https://openlibrary.org/subjects/{subject}.json';
    private const COVER_URL    = 'https://covers.openlibrary.org/b/id/{id}-L.jpg';
    private const USER_AGENT   = 'BookHub/1.0 (educational; contact: admin@bookhub.com)';
    private const CACHE_TTL    = 3600;

    /**
     * Cliente HTTP con verificación SSL configurada (fix XAMPP Windows).
     */
    private function http()
    {
        $verify = env('CURL_CA_BUNDLE') ?: true;

        return Http::withHeaders(['User-Agent' => self::USER_AGENT])
            ->timeout(10)
            ->withOptions(['verify' => $verify]);
    }

    /**
     * Búsqueda ligera. Devuelve resultados simplificados para autocompletado.
     *
     * @return array<int, array{key:string, title:string, author:?string, year:?int, cover_url:?string}>
     */
    public function search(string $query, int $limit = 10): array
    {
        $query = trim($query);
        if ($query === '' || mb_strlen($query) < 2) {
            return [];
        }

        $limit  = max(1, min($limit, 20));
        $cacheKey = 'ol:search:' . md5(mb_strtolower($query)) . ":{$limit}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($query, $limit) {
            $response = $this->http()->get(self::SEARCH_URL, [
                'q'      => $query,
                'limit'  => $limit,
                'fields' => 'key,title,author_name,first_publish_year,cover_i',
            ]);

            if (! $response->successful()) {
                return [];
            }

            $docs = $response->json('docs') ?? [];

            return collect($docs)->map(fn($doc) => [
                'key'       => $doc['key'] ?? null,
                'title'     => $doc['title'] ?? '(sin título)',
                'author'    => isset($doc['author_name'][0]) ? $doc['author_name'][0] : null,
                'year'      => $doc['first_publish_year'] ?? null,
                'cover_url' => isset($doc['cover_i'])
                    ? str_replace('{id}', $doc['cover_i'], self::COVER_URL)
                    : null,
            ])->filter(fn($r) => $r['key'] !== null)->values()->all();
        });
    }

    /**
     * Lista de obras populares de un subject (categoría) de Open Library.
     *
     * @return array<int, array{key:string, title:string, author:?string, year:?int, cover_url:?string}>
     */
    public function getBySubject(string $subject, int $limit = 12): array
    {
        $subject = trim(mb_strtolower($subject));
        if ($subject === '') {
            return [];
        }

        $limit    = max(1, min($limit, 50));
        $cacheKey = 'ol:subject:' . md5($subject) . ":{$limit}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($subject, $limit) {
            $url = str_replace('{subject}', rawurlencode($subject), self::SUBJECT_URL);

            $response = $this->http()->get($url, ['limit' => $limit]);

            if (! $response->successful()) {
                return [];
            }

            $works = $response->json('works') ?? [];

            return collect($works)->map(fn($w) => [
                'key'       => $w['key'] ?? null,
                'title'     => $w['title'] ?? null,
                'author'    => isset($w['authors'][0]['name']) ? $w['authors'][0]['name'] : null,
                'year'      => $w['first_publish_year'] ?? null,
                'cover_url' => !empty($w['cover_id'])
                    ? str_replace('{id}', (string) $w['cover_id'], self::COVER_URL)
                    : null,
            ])->filter(fn($r) => !empty($r['title']) && !empty($r['key']))->values()->all();
        });
    }

    /**
     * Detalle de un work. Devuelve descripción + subjects normalizados.
     *
     * @return array{description:?string, subjects:array, cover_url:?string, title:?string}
     */
    public function getWork(string $workKey): array
    {
        // Validar formato: debe ser /works/OL...W
        if (! preg_match('#^/works/OL\d+W$#', $workKey)) {
            return ['description' => null, 'subjects' => [], 'cover_url' => null, 'title' => null];
        }

        $cacheKey = 'ol:work:' . md5($workKey);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($workKey) {
            $url = str_replace('{key}', ltrim($workKey, '/'), self::WORK_URL);

            $response = $this->http()->get($url);

            if (! $response->successful()) {
                return ['description' => null, 'subjects' => [], 'cover_url' => null, 'title' => null];
            }

            $data = $response->json();

            // Description puede ser string o {type, value}
            $description = $data['description'] ?? null;
            if (is_array($description)) {
                $description = $description['value'] ?? null;
            }

            // Cover: primer ID válido del array
            $coverId  = $data['covers'][0] ?? null;
            $coverUrl = $coverId ? str_replace('{id}', $coverId, self::COVER_URL) : null;

            return [
                'title'       => $data['title'] ?? null,
                'description' => $description,
                'subjects'    => array_slice($data['subjects'] ?? [], 0, 30),
                'cover_url'   => $coverUrl,
            ];
        });
    }
}
