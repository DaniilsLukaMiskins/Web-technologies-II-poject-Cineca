<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $stats = $user->statistics;
        return view('profile.show', compact('user', 'stats'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'updated avatar',
            'entity_type' => 'user',
            'entity_id'   => $user->id,
            'created_at'  => now(),
        ]);

        return redirect()->back()->with('success', __('messages.avatar_updated'));
    }

    public function reviews()
    {
        $reviews = auth()->user()->reviews()->with('movie')->latest()->get();
        return view('profile.reviews', compact('reviews'));
    }
}