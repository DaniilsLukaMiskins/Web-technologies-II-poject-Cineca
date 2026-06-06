<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $stats = $user->statistics;
        return view('profile.show', compact('user', 'stats'));
    }
}