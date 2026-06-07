@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4" style="color:#F0F465;">{{ __('messages.movies') }}</h1>
    <div class="text-center mt-4 mb-3">
        <small style="color: rgba(255,255,255,0.5);">{{ __('messages.provided_by') }}</small><br>
        <img src="{{ asset('images/tmdb_logo.svg') }}" alt="TMDB" style="height: 20px; margin-top: 5px;">
    </div>
    <form action="{{ route('movies.search') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control"
                   placeholder="{{ __('messages.search') }}..." value="{{ $query ?? '' }}">
            <button class="btn btn-primary" type="submit">{{ __('messages.search') }}</button>
        </div>
    </form>

    <div class="row row-cols-2 row-cols-md-4 g-4">
        @foreach($movies as $movie)
        <div class="col">
            <div class="card h-100">
                @if($movie['poster_path'])
                <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}"
                     class="card-img-top" alt="{{ $movie['title'] }}"
                     style="height:300px; object-fit:cover;">
                @endif
                <div class="card-body">
                    <h6 class="card-title">{{ $movie['title'] }}</h6>
                    <p class="card-text">
                        <small>⭐ {{ number_format($movie['vote_average'], 1) }}</small>
                    </p>
                    <a href="{{ route('movies.show', $movie['id']) }}"
                       class="btn btn-primary btn-sm">{{ __('messages.view') }}</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection