<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Movie;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
{
    $watchlist = Auth::user()->watchlist()->with('movie')->get();
    
    $tmdb = new \App\Services\TmdbService();
    
    foreach ($watchlist as $item) {
        $movieData = $tmdb->getMovie($item->movie->tmdb_movie_id);
        $item->poster = $movieData['poster_path'] ?? null;
    }
    
    return view('watchlist.index', compact('watchlist'));
}

    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'status'   => 'required|in:want_to_watch,watching,watched',
        ]);

        Watchlist::firstOrCreate(
            ['user_id' => Auth::id(), 'movie_id' => $request->movie_id],
            ['status' => $request->status]
        );

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'added to watchlist',
            'entity_type' => 'movie',
            'entity_id'   => $request->movie_id,
            'created_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Added to watchlist!');
    }

    public function update(Request $request, Watchlist $watchlist)
    {
        $request->validate([
            'status' => 'required|in:want_to_watch,watching,watched',
        ]);

        $watchlist->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Watchlist updated!');
    }

    public function destroy(Watchlist $watchlist)
    {
        $watchlist->delete();
        return redirect()->back()->with('success', 'Removed from watchlist!');
    }
}