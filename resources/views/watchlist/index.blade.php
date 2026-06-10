@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4" style="color:#F0F465;">{{ __('messages.my_watchlist') }}</h1>

    <div class="text-center mt-4 mb-3">
        <small style="color: rgba(255,255,255,0.5);">{{ __('messages.provided_by') }}</small><br>
        <img src="{{ asset('images/tmdb_logo.svg') }}" alt="TMDB" style="height: 20px; margin-top: 5px;">
    </div>

    @if($watchlist->isEmpty())
        <p>Your watchlist is empty. Go watch some movies!</p>
        <a href="{{ route('movies.index') }}" class="btn btn-primary">{{ __('messages.browse_movies') }}</a>
    @endif

    <div class="row row-cols-2 row-cols-md-4 g-4 align-items-start">
        @foreach($watchlist as $item)
        <div class="col">
            <div class="card h-100">
                @if($item->poster)
                    <img src="https://image.tmdb.org/t/p/w300{{ $item->poster }}"
     class="card-img-top" alt="{{ $item->movie->title }}"
     style="height:300px; object-fit:cover;">
                @endif
                <div class="card-body">
                    <h2 class="card-title" style="font-size:1rem; min-height:48px; overflow:hidden;">{{ $item->movie->title }}</h2>

                    <form action="{{ route('watchlist.update', $item) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select bg-dark text-light border-secondary mb-2"
                                onchange="this.form.submit()">
                            <option value="want_to_watch" {{ $item->status === 'want_to_watch' ? 'selected' : '' }}>
                                {{ __('messages.want_to_watch') }}
                            </option>
                            <option value="watching" {{ $item->status === 'watching' ? 'selected' : '' }}>
                                {{ __('messages.watching') }}
                            </option>
                            <option value="watched" {{ $item->status === 'watched' ? 'selected' : '' }}>
                                {{ __('messages.watched') }}
                            </option>
                        </select>
                    </form>

                    <div class="d-flex gap-2">
                        <a href="{{ route('movies.show', $item->movie->tmdb_movie_id) }}"
                           class="btn btn-sm btn-outline-light">{{ __('messages.view') }}</a>

                        <form action="{{ route('watchlist.destroy', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">{{ __('messages.remove') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection