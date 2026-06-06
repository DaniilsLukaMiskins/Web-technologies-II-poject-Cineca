@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4" style="color:#F0F465;">My Watchlist</h1>

    @if($watchlist->isEmpty())
        <p>Your watchlist is empty. Go watch some movies!</p>
        <a href="{{ route('movies.index') }}" class="btn btn-primary">Browse Movies</a>
    @endif

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($watchlist as $item)
        <div class="col">
            <div class="card h-100">
                @if($item->poster)
<img src="https://image.tmdb.org/t/p/w300{{ $item->poster }}"
     class="card-img-top" alt="{{ $item->movie->title }}">
@endif
                <div class="card-body">
                    <h5 class="card-title">{{ $item->movie->title }}</h5>

                    <form action="{{ route('watchlist.update', $item) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select bg-dark text-light border-secondary mb-2"
                                onchange="this.form.submit()">
                            <option value="want_to_watch"
                                {{ $item->status === 'want_to_watch' ? 'selected' : '' }}>
                                Want to Watch
                            </option>
                            <option value="watching"
                                {{ $item->status === 'watching' ? 'selected' : '' }}>
                                Watching
                            </option>
                            <option value="watched"
                                {{ $item->status === 'watched' ? 'selected' : '' }}>
                                Watched
                            </option>
                        </select>
                    </form>

                    <div class="d-flex gap-2">
                        <a href="{{ route('movies.show', $item->movie->tmdb_movie_id) }}"
                           class="btn btn-sm btn-outline-light">View</a>

                        <form action="{{ route('watchlist.destroy', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection