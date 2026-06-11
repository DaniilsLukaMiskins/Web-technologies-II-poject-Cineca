@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h1 class="mb-4 fw-bold" style="color:#F0F465;">
                    {{ __('messages.write_review_title') }}
                </h1>
                <h5 class="mb-4">{{ $movie->title }}</h5>

                <form action="{{ route('reviews.store') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                    <input type="hidden" name="tmdb_id" value="{{ $movie->tmdb_movie_id }}">

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.grade') }}</label>
                        <input type="number" name="grade" class="form-control"
                               min="1" max="10" required>
                        @error('grade')
                            <small class="text-warning">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">{{ __('messages.review_text') }}</label>
                        <textarea name="text" class="form-control"
                                  rows="5" placeholder="{{ __('messages.review_placeholder') }}">{{ old('text') }}</textarea>
                        @error('text')
                            <small class="text-warning">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        {{ __('messages.submit_review') }}
                    </button>
                    <a href="{{ route('movies.show', $movie->tmdb_movie_id) }}"
                       class="btn btn-outline-light w-100 mt-2">
                        {{ __('messages.cancel') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection