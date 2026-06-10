<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('admin.index', compact('users'));
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update(['role_id' => $request->role_id]);

        return redirect()->back()->with('success', ->with('success', __('messages.role_updated')));
    }

    public function log()
    {
        $logs = AuditLog::with('user')->latest()->paginate(50);
        return view('admin.log', compact('logs'));
    }
}