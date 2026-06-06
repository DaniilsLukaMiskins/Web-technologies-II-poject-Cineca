<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Movie;
use App\Models\AuditLog;
use App\Models\UserStatistic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Update user statistics after review changes
    private function updateUserStats(int $userId): void
    {
        $user = User::find($userId);
        $reviews = $user->reviews()->with('movie')->get();

        $tmdb = new \App\Services\TmdbService();
        $genreCounts = [];
        foreach ($reviews as $r) {
            $movieData = $tmdb->getMovie($r->movie->tmdb_movie_id);
            foreach ($movieData['genres'] ?? [] as $genre) {
                $genreCounts[$genre['name']] = ($genreCounts[$genre['name']] ?? 0) + 1;
            }
        }
        arsort($genreCounts);
        $favouriteGenre = !empty($genreCounts) ? array_key_first($genreCounts) : null;

        UserStatistic::updateOrCreate(
            ['user_id' => $userId],
            [
                'amount_of_reviews' => $reviews->count(),
                'average_grade'     => round($reviews->avg('grade'), 2),
                'favourite_genre'   => $favouriteGenre,
            ]
        );
    }

    public function create(int $tmdbId)
    {
        $movie = Movie::where('tmdb_movie_id', $tmdbId)->firstOrFail();
        return view('reviews.create', compact('movie'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'text'     => 'nullable|string|max:2000',
            'grade'    => 'required|integer|min:1|max:10',
        ]);

        $review = Review::create([
            'user_id'  => Auth::id(),
            'movie_id' => $request->movie_id,
            'text'     => $request->text,
            'grade'    => $request->grade,
        ]);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'created review',
            'entity_type' => 'review',
            'entity_id'   => $review->id,
            'created_at'  => now(),
        ]);

        // Update statistics
        $this->updateUserStats(Auth::id());

        return redirect()->route('movies.show', $request->tmdb_id)
            ->with('success', 'Review added!');
    }

    public function edit(Review $review)
    {
        if (Auth::id() !== $review->user_id && !Auth::user()->isModerator()) {
            abort(403);
        }
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        if (Auth::id() !== $review->user_id && !Auth::user()->isModerator()) {
            abort(403);
        }

        $request->validate([
            'text'  => 'nullable|string|max:2000',
            'grade' => 'required|integer|min:1|max:10',
        ]);

        $review->update($request->only('text', 'grade'));

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'updated review',
            'entity_type' => 'review',
            'entity_id'   => $review->id,
            'created_at'  => now(),
        ]);

        return redirect()->route('movies.show', $review->movie->tmdb_movie_id)
            ->with('success', 'Review updated!');
    }

    public function destroy(Review $review)
    {
        if (Auth::id() !== $review->user_id && !Auth::user()->isModerator()) {
            abort(403);
        }

        $userId = $review->user_id;

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'deleted review',
            'entity_type' => 'review',
            'entity_id'   => $review->id,
            'created_at'  => now(),
        ]);

        $review->delete();

        // Update statistics after deletion
        $this->updateUserStats($userId);

        return redirect()->back()->with('success', 'Review deleted!');
    }
}