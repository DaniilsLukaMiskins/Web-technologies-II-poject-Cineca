<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cineca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #533A71;
            color: #ffffff;
        }
        .navbar {
            background-color: #6184D8 !important;
        }
        .navbar-brand {
            color: #F0F465 !important;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .btn-primary {
            background-color: #50C5B7;
            border-color: #50C5B7;
            color: #000;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #9CEC5B;
            border-color: #9CEC5B;
            color: #000;
        }
        .btn-outline-light:hover {
            background-color: #F0F465;
            color: #000;
            border-color: #F0F465;
        }
        .card {
            background-color: #6184D8 !important;
            border: none;
        }
        .form-control {
            background-color: #ffffff !important;
            color: #000000 !important;
            border-color: #50C5B7 !important;
        }
        .form-control:focus {
            border-color: #6184D8 !important;
            box-shadow: 0 0 0 0.2rem rgba(97, 132, 216, 0.25) !important;
        }
        .alert-success {
            background-color: #9CEC5B;
            color: #000;
            border: none;
        }
        .alert-danger {
            background-color: #F0F465;
            color: #000;
            border: none;
        }
        a {
            color: #F0F465;
        }
        a:hover {
            color: #9CEC5B;
        }
    </style>
</head>
<body>

<nav class="navbar px-4">
    <a class="navbar-brand" href="/">Cineca</a>
    <div class="d-flex gap-2 align-items-center">
        <a href="{{ route('lang.switch', 'en') }}" class="btn btn-sm btn-outline-light">EN</a>
    <a href="{{ route('lang.switch', 'lv') }}" class="btn btn-sm btn-outline-light">LV</a>
        @auth
            <a href="/watchlist" class="btn btn-outline-light btn-sm">{{ __('messages.watchlist') }}</a>
            <a href="{{ route('profile.reviews') }}" class="btn btn-outline-light btn-sm">
                {{ __('messages.reviews') }}
            </a>
<a href="/friends" class="btn btn-outline-light btn-sm">{{ __('messages.friends') }}</a>
            @if(auth()->user()->isAdmin())
                <a href="/admin" class="btn btn-sm"
                   style="background-color:#F0F465; color:#000; font-weight:bold;">{{ __('messages.admin') }}</a>
            @endif
            <a href="/profile" class="btn btn-outline-light btn-sm">
                {{ auth()->user()->username }}
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-sm"
                        style="background-color:#50C5B7; color:#000; font-weight:bold;">{{ __('messages.logout') }}
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">{{ __('messages.login') }}</a>
            <a href="{{ route('register') }}" class="btn btn-sm"
               style="background-color:#50C5B7; color:#000; font-weight:bold;">{{ __('messages.register') }}</a>
        @endauth
    </div>
</nav>

<main class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>