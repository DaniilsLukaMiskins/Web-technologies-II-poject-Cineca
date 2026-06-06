<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supported = ['en', 'lv'];

        if (session()->has('locale')) {
            $locale = session('locale');
        } else {
            $browserLocale = substr($request->header('Accept-Language', 'en'), 0, 2);
            $locale = in_array($browserLocale, $supported) ? $browserLocale : 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}