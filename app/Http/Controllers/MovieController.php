<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    private TmdbService $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    public function index()
    {
        $data = $this->tmdb->getPopularMovies();
        $movies = $data['results'] ?? [];
        return view('movies.index', compact('movies'));
    }

    public function show(int $tmdbId)
    {
        $movieData = $this->tmdb->getMovie($tmdbId);
        $movie = Movie::firstOrCreate(
            ['tmdb_movie_id' => $tmdbId],
            ['title' => $movieData['title']]
        );
        $reviews = $movie->reviews()->with('user')->latest()->get();
        return view('movies.show', compact('movieData', 'movie', 'reviews'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $data = $this->tmdb->searchMovies($query);
        $movies = $data['results'] ?? [];
        return view('movies.index', compact('movies', 'query'));
    }
}