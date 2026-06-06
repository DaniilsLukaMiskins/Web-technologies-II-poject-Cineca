@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4" style="color:#F0F465;">My Profile</h1>

    <div class="row">
        {{-- Profile info --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="rounded-circle d-inline-flex align-items-center
                                justify-content-center mb-3"
                         style="width:100px; height:100px;
                                background-color:#6184D8; font-size:2rem;">
                        👤
                    </div>
                    <h4>{{ auth()->user()->username }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <span class="badge"
                          style="background-color:#F0F465; color:#000; font-size:0.9rem;">
                        {{ auth()->user()->role->name }}
                    </span>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="card">
                <div class="card-body">
                    <h5 style="color:#F0F465;">Statistics</h5>
                    @if($stats)
                    <p>Reviews: <strong>{{ $stats->amount_of_reviews }}</strong></p>
                    <p>Average grade: ⭐ <strong>{{ number_format($stats->average_grade, 1) }}</strong></p>
                    <p>Favourite genre: <strong>{{ $stats->favourite_genre ?? 'N/A' }}</strong></p>
                    @else
                    <p>No statistics yet. Start reviewing movies!</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent reviews --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 style="color:#F0F465;">My Recent Reviews</h5>
                    @if(auth()->user()->reviews->isEmpty())
                        <p>You haven't reviewed any movies yet.</p>
                        <a href="{{ route('movies.index') }}" class="btn btn-primary btn-sm">
                            Browse Movies
                        </a>
                    @endif
                    @foreach(auth()->user()->reviews()->with('movie')->latest()->take(5)->get() as $review)
                    <div class="card mb-2" style="background-color:#533A71;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('movies.show', $review->movie->tmdb_movie_id) }}"
                                   class="text-light">
                                    {{ $review->movie->title }}
                                </a>
                                <span class="badge"
                                      style="background-color:#F0F465; color:#000;">
                                    {{ $review->grade }}/10
                                </span>
                            </div>
                            <p class="mt-1 mb-0 small">{{ $review->text }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection