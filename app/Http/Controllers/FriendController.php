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
            'friend_id' => 'required|exists:users,id',
        ]);

        Friend::firstOrCreate([
            'user_id'   => Auth::id(),
            'friend_id' => $request->friend_id,
            'status'    => 'pending',
        ]);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'sent friend request',
            'entity_type' => 'user',
            'entity_id'   => $request->friend_id,
            'created_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Friend request sent!');
    }

    public function update(Request $request, Friend $friend)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $friend->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Friend request updated!');
    }

    public function destroy(Friend $friend)
    {
        $friend->delete();
        return redirect()->back()->with('success', 'Friend removed!');
    }
}