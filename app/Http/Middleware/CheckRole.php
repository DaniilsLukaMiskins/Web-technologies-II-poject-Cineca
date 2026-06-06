<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (!in_array(auth()->user()->role->name, $roles)) {
            return redirect('/')->with('error', 'You do not have access to this page.');
        }

        return $next($request);
    }
}