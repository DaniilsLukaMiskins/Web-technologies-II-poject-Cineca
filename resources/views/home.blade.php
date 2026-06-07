@extends('layouts.app')

@section('content')
<div class="container">
        <div class="text-center mt-4 mb-3">
            <small style="color: rgba(255,255,255,0.5);">{{ __('messages.provided_by') }}</small><br>
            <img src="{{ asset('images/tmdb_logo.svg') }}" alt="TMDB" style="height: 20px; margin-top: 5px;">
        </div>
    {{-- Search bar --}}
    <div class="row justify-content-center mb-5 mt-3">
        <div class="col-md-8">
            <form action="{{ route('movies.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control form-control-lg"
                           placeholder="{{ __('messages.search') }}...">
                    <button class="btn btn-primary btn-lg" type="submit">{{ __('messages.search') }}</button>
                </div>
            </form>
        </div>
    </div>

    @auth
        {{-- Recently reviewed --}}
        @if(isset($recentReviews) && $recentReviews->isNotEmpty())
        <h2 style="color:#F0F465;">{{ __('messages.recently_reviewed') }}</h2>
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
        <h2 style="color:#F0F465;">{{ __('messages.recommendations') }}</h2>
        <div class="row row-cols-2 row-cols-md-4 g-4 mb-5">
            @foreach(array_slice($recommendations, 0, 8) as $movie)
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
        @endif
    @else
        {{-- Guest message --}}
        <div class="text-center mt-5">
            <h1 style="color:#F0F465;">{{ __('messages.welcome') }}</h1>
            <p class="lead">{{ __('messages.tagline') }}</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">
    {{ __('messages.get_started') }}
</a>            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                Login
            </a>
        </div>


        
    @endauth

</div>
@endsection