@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')
@section('content')
<div class="container">
    <h1 class="mb-4" style="color:#F0F465;">{{ __('messages.my_profile') }}</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                             class="rounded-circle mb-3"
                             style="width:100px; height:100px; object-fit:cover;">
                    @else
                        <div class="rounded-circle d-inline-flex align-items-center
                                    justify-content-center mb-3"
                             style="width:100px; height:100px;
                                    background-color:#6184D8; font-size:2rem;">
                            👤
                        </div>
                    @endif
                    <h4>{{ auth()->user()->username }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <span class="badge"
                          style="background-color:#F0F465; color:#000; font-size:0.9rem;">
                        {{ __('messages.role_' . auth()->user()->role->name) }}
                    </span>

                    <form action="{{ route('profile.avatar') }}" method="POST"
                          enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <input type="file" name="avatar" class="form-control mb-2" accept="image/*">
                        @error('avatar')
                            <small class="text-warning">{{ $message }}</small>
                        @enderror
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('messages.upload_avatar') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 style="color:#F0F465;">{{ __('messages.statistics') }}</h5>
                    @if($stats)
                      <p>{{ __('messages.amount_of_reviews') }}: <strong>{{ $stats->amount_of_reviews }}</strong></p>
                      <p>{{ __('messages.average_grade') }}: ⭐ <strong>{{ number_format($stats->average_grade, 1) }}</strong></p>
                      <p>{{ __('messages.favourite_genre') }}: <strong>{{ $stats->favourite_genre ?? __('messages.not_available') }}</strong></p>
                    @else
                        <p>{{ __('messages.no_statistics') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 style="color:#F0F465;">{{ __('messages.reviews') }}</h5>
                    @if(auth()->user()->reviews->isEmpty())
                        <p>{{ __('messages.no_reviews') }}</p>
                        <a href="{{ route('movies.index') }}" class="btn btn-primary btn-sm">
                            {{ __('messages.browse_movies') }}
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
                                <span class="badge" style="background-color:#F0F465; color:#000;">
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