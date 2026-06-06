<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index()
    {
        $friends = Friend::where('user_id', Auth::id())
            ->where('status', 'accepted')
            ->with('friend')
            ->get();

        $pending = Friend::where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->with('user')
            ->get();

        return view('friends.index', compact('friends', 'pending'));
    }

    public function store(Request $request)
{
    $request->validate([
        'username' => 'required|exists:users,username',
    ]);

    $friend = \App\Models\User::where('username', $request->username)->first();

    if ($friend->id === Auth::id()) {
        return redirect()->back()->with('error', 'You cannot add yourself!');
    }

    Friend::firstOrCreate([
        'user_id'   => Auth::id(),
        'friend_id' => $friend->id,
        'status'    => 'pending',
    ]);

    return redirect()->back()->with('success', 'Friend request sent!');
}
   public function update(Request $request, Friend $friend)
{
    $request->validate([
        'status' => 'required|in:accepted,rejected',
    ]);

    $friend->update(['status' => $request->status]);

    if ($request->status === 'accepted') {
        Friend::firstOrCreate([
            'user_id'   => $friend->friend_id,
            'friend_id' => $friend->user_id,
            'status'    => 'accepted',
        ]);
    }

    return redirect()->back()->with('success', 'Friend request updated!');
}

    public function destroy(Friend $friend)
    {
        $friend->delete();
        return redirect()->back()->with('success', 'Friend removed!');
    }
}