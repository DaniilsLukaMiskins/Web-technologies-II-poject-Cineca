@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Search bar --}}
    <div class="row justify-content-center mb-5 mt-3">
        <div class="col-md-8">
            <form action="{{ route('movies.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control form-control-lg"
                           placeholder="Search movies...">
                    <button class="btn btn-primary btn-lg" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>

    @auth
        {{-- Recently reviewed --}}
        @if(isset($recentReviews) && $recentReviews->isNotEmpty())
        <h2 style="color:#F0F465;">Recently Reviewed</h2>
        <div class="row row-cols-2 row-cols-md-5 g-3 mb-5">
            @foreach($recentReviews as $review)
            <div class="col">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <a href="{{ route('movies.show', $review->movie->tmdb_movie_id) }}"
                           class="text-light text-decoration-none">
                            {{ $review->movie->title }}
                        </a>
                        <div class="mt-2">
                            <span class="badge"
                                  style="background-color:#F0F465; color:#000;">
                                {{ $review->grade }}/10
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Recommendations --}}
        @if(isset($recommendations) && count($recommendations) > 0)
        <h2 style="color:#F0F465;">Recommendations for you</h2>
        <div class="row row-cols-2 row-cols-md-5 g-3 mb-5">
            @foreach(array_slice($recommendations, 0, 10) as $movie)
            <div class="col">
                <div class="card h-100">
                    @if($movie['poster_path'])
                    <img src="https://image.tmdb.org/t/p/w200{{ $movie['poster_path'] }}"
                         class="card-img-top" alt="{{ $movie['title'] }}">
                    @endif
                    <div class="card-body">
                        <a href="{{ route('movies.show', $movie['id']) }}"
                           class="text-light text-decoration-none small">
                            {{ $movie['title'] }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    @else
        {{-- Guest message --}}
        <div class="text-center mt-5">
            <h1 style="color:#F0F465;">Welcome to CineCA</h1>
            <p class="lead">Your personal movie tracker</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">
                Get Started
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                Login
            </a>
        </div>
    @endauth

</div>
@endsection