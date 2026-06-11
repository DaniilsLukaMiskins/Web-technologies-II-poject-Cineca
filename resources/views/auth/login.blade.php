@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h1 class="visually-hidden">Cineca - Login</h1>
                <h2 class="text-center mb-4 fw-bold" style="color:#F0F465;">{{ __('messages.login_title') }}</h2>
                <form action="{{ route('login') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.email') }}</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}" required>
                        @error('email')
                            <small class="text-warning">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">{{ __('messages.password') }}</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        {{ __('messages.login') }}
                    </button>
                    <p class="text-center mt-3 mb-0">
                        {{ __('messages.no_account') }}
                        <a href="{{ route('register') }}">{{ __('messages.register') }}</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection