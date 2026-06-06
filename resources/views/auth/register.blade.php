@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h2 class="text-center mb-4 fw-bold" style="color:#F0F465;">Register user</h2>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control"
                               value="{{ old('username') }}" required>
                        @error('username')
                            <small class="text-warning">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}" required>
                        @error('email')
                            <small class="text-warning">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password')
                            <small class="text-warning">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                               class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        Register
                    </button>
                    <p class="text-center mt-3 mb-0">
                        Already have an account?
                        <a href="{{ route('login') }}">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection