<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.themoviedb.org/3';

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.key');
    }

    public function getPopularMovies(int $page = 1): array
    {
        $response = Http::get("{$this->baseUrl}/movie/popular", [
            'api_key' => $this->apiKey,
            'page' => $page,
            'language' => 'en-US',
        ]);

        return $response->json();
    }

    public function searchMovies(string $query, int $page = 1): array
    {
        $response = Http::get("{$this->baseUrl}/search/movie", [
            'api_key' => $this->apiKey,
            'query' => $query,
            'page' => $page,
            'language' => 'en-US',
        ]);

        return $response->json();
    }

    public function getMovie(int $tmdbId): array
    {
        $response = Http::get("{$this->baseUrl}/movie/{$tmdbId}", [
            'api_key' => $this->apiKey,
            'language' => 'en-US',
        ]);

        return $response->json();
    }

    public function getGenres(): array
    {
        $response = Http::get("{$this->baseUrl}/genre/movie/list", [
            'api_key' => $this->apiKey,
            'language' => 'en-US',
        ]);

        return $response->json();
    }
}