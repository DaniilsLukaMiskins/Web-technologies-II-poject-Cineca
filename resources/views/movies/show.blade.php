@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @if($movieData['poster_path'])
                <img src="https://image.tmdb.org/t/p/w300{{ $movieData['poster_path'] }}"
                     class="img-fluid rounded" alt="{{ $movieData['title'] }}">
            @else
                <div class="bg-secondary d-flex align-items-center justify-content-center rounded"
                     style="height:400px;">
                    <span>No Image</span>
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <h1 style="color:#F0F465;">{{ $movieData['title'] }}</h1>
            <p>{{ $movieData['overview'] }}</p>
            <p>⭐ {{ number_format($movieData['vote_average'], 1) }}/10</p>
            <p>Release date: {{ $movieData['release_date'] }}</p>
            <p>Genres:
                @foreach($movieData['genres'] ?? [] as $genre)
                    <span class="badge" style="background-color:#6184D8; color:#fff;">
                        {{ $genre['name'] }}
                    </span>
                @endforeach
            </p>

            @auth
            <div class="d-flex gap-2 mb-3">
                @php
                    $inWatchlist = auth()->user()->watchlist()
                        ->where('movie_id', $movie->id)->exists();
                @endphp

                @if($inWatchlist)
                    <button class="btn btn-success" disabled>✓ {{ __('messages.watchlist') }}</button>
                @else
                    <form action="{{ route('watchlist.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                        <input type="hidden" name="status" value="want_to_watch">
                        <button class="btn btn-primary">+ {{ __('messages.watchlist') }}</button>
                    </form>
                @endif

                <a href="{{ route('reviews.create', $movieData['id']) }}"
                class="btn"
                style="background-color:#9CEC5B; color:#000; font-weight:bold;">
                    {{ __('messages.write_review') }}
                </a>
            </div>
            @endauth
        </div>
    </div>

    <h2 class="mt-4" style="color:#F0F465;">{{ __('messages.reviews') }}</h2>

    @if($reviews->isEmpty())
        <p>{{ __('messages.no_reviews') }}</p>
    @endif

    @foreach($reviews as $review)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <strong>{{ $review->user->username }}</strong>
                <span class="badge"
                      style="background-color:#F0F465; color:#000; font-size:1rem;">
                    {{ $review->grade }}/10
                </span>
            </div>
            <p class="mt-2 mb-1">{{ $review->text }}</p>
            @auth
                @if(auth()->user()->id === $review->user_id || auth()->user()->isModerator())
                <a href="{{ route('reviews.edit', $review) }}"
                   class="btn btn-sm btn-outline-light">{{ __('messages.edit') }}</a>
                @endif
                @can('delete', $review)
                <form action="{{ route('reviews.destroy', $review) }}"
                      method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">{{ __('messages.delete') }}</button>
                </form>
                @endcan
            @endauth
        </div>
    </div>
    @endforeach
</div>
@endsection