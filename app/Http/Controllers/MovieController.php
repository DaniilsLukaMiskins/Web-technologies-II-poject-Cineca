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
        $query = $request->get('q', '');
    
        if (empty($query)) {
            return redirect()->route('movies.index');
        }
        $data = $this->tmdb->searchMovies($query);
        $movies = $data['results'] ?? [];
        return view('movies.index', compact('movies', 'query'));
    }

  public function home()
{
    $recentReviews = collect();
    $recommendations = [];

    if (auth()->check()) {
        $user = auth()->user();

        $recentReviews = $user->reviews()
            ->with('movie')
            ->latest()
            ->take(5)
            ->get();

        // Get IDs of movies user already reviewed
        $reviewedTmdbIds = $user->reviews()
            ->with('movie')
            ->get()
            ->pluck('movie.tmdb_movie_id')
            ->toArray();

        $stats = $user->statistics;
        if ($stats && $stats->favourite_genre) {
            $data = $this->tmdb->getMoviesByGenre($stats->favourite_genre);
            $allMovies = $data['results'] ?? [];
            
            // Get IDs of movies in watchlist
            $watchlistTmdbIds = $user->watchlist()
                ->with('movie')
                ->get()
                ->pluck('movie.tmdb_movie_id')
                ->toArray();

            // Filter out already reviewed AND watchlisted movies
            $excludedIds = array_merge($reviewedTmdbIds, $watchlistTmdbIds);

            $recommendations = array_filter($allMovies, function($movie) use ($excludedIds) {
                return !in_array($movie['id'], $excludedIds);
            });

            $recommendations = array_values($recommendations);
        }
    }

    return view('home', compact('recentReviews', 'recommendations'));
}
}