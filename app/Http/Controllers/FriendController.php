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

        $friend = User::where('username', $request->username)->first();

        if ($friend->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot add yourself!');
        }

        // check if it is already a friend
        $existing = Friend::where(function($query) use ($friend) {
            $query->where('user_id', Auth::id())
                ->where('friend_id', $friend->id);
        })->orWhere(function($query) use ($friend) {
            $query->where('user_id', $friend->id)
                ->where('friend_id', Auth::id());
        })->first();

        if ($existing) {
            if ($existing->status === 'accepted') {
                return redirect()->back()->with('error', 'This user is already your friend!');
            }
            if ($existing->status === 'pending') {
                return redirect()->back()->with('error', 'Friend request already sent!');
            }
        }

        Friend::create([
            'user_id'   => Auth::id(),
            'friend_id' => $friend->id,
            'status'    => 'pending',
        ]);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'sent friend request',
            'entity_type' => 'user',
            'entity_id'   => $friend->id,
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

        if ($request->status === 'accepted') {
            Friend::firstOrCreate([
                'user_id'   => $friend->friend_id,
                'friend_id' => $friend->user_id,
                'status'    => 'accepted',
            ]);
        }

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => $request->status === 'accepted' ? 'accepted friend request' : 'rejected friend request',
            'entity_type' => 'user',
            'entity_id'   => $friend->user_id,
            'created_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Friend request updated!');
    }

    public function destroy(Friend $friend)
    {
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'removed friend',
            'entity_type' => 'user',
            'entity_id'   => $friend->friend_id,
            'created_at'  => now(),
        ]);

        // deleting for both users
        Friend::where(function($query) use ($friend) {
            $query->where('user_id', $friend->user_id)
                ->where('friend_id', $friend->friend_id);
        })->orWhere(function($query) use ($friend) {
            $query->where('user_id', $friend->friend_id)
                ->where('friend_id', $friend->user_id);
        })->delete();

        return redirect()->back()->with('success', 'Friend removed!');
    }
}