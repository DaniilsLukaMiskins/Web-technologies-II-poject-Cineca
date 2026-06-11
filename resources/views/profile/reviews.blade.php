@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4" style="color:#F0F465;">{{ __('messages.my_reviews') }}</h1>
    <div class="text-center mt-4 mb-3">
        <small style="color: rgba(255,255,255,0.5);">{{ __('messages.provided_by') }}</small><br>
        <img src="{{ asset('images/tmdb_logo.svg') }}" alt="TMDB" style="height: 20px; margin-top: 5px;">
    </div>
    @if($reviews->isEmpty())
        <p>{{ __('messages.no_reviews') }}</p>
        <a href="{{ route('movies.index') }}" class="btn btn-primary">{{ __('messages.browse_movies') }}</a>
    @else
        <div class="row row-cols-1 g-3">
            @foreach($reviews as $review)
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('movies.show', $review->movie->tmdb_movie_id) }}"
                               class="text-light fw-bold fs-5">
                                {{ $review->movie->title }}
                            </a>
                            <span class="badge"
                                  style="background-color:#F0F465; color:#000; font-size:1rem;">
                                {{ $review->grade }}/10
                            </span>
                        </div>
                        <p class="mt-2 mb-2">{{ $review->text }}</p>
                        <small style="color:rgba(255,255,255,0.5);">
                            {{ $review->created_at->format('d.m.Y') }}
                        </small>
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('reviews.edit', $review) }}"
                               class="btn btn-sm btn-outline-light">{{ __('messages.edit') }}</a>
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">{{ __('messages.delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection