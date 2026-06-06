@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4" style="color:#F0F465;">Friends</h1>

    {{-- Incoming requests --}}
    @if($pending->isNotEmpty())
    <h2 class="mb-3" style="color:#9CEC5B;">Friend Requests</h2>
    @foreach($pending as $request)
    <div class="card mb-2">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span>{{ $request->user->username }} wants to be your friend</span>
            <div class="d-flex gap-2">
                <form action="{{ route('friends.update', $request) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="accepted">
                    <button class="btn btn-sm"
                            style="background-color:#9CEC5B; color:#000; font-weight:bold;">
                        Accept
                    </button>
                </form>
                <form action="{{ route('friends.update', $request) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <button class="btn btn-sm btn-danger">Reject</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    {{-- Friends list --}}
    <h2 class="mt-4 mb-3" style="color:#F0F465;">My Friends</h2>

    @if($friends->isEmpty())
        <p>You have no friends yet.</p>
    @endif

    @foreach($friends as $friend)
    <div class="card mb-2">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span>{{ $friend->friendUser->username }}</span>
            <form action="{{ route('friends.destroy', $friend) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Remove</button>
            </form>
        </div>
    </div>
    @endforeach

    {{-- Adding friend --}}
    <h2 class="mt-4 mb-3" style="color:#F0F465;">Add Friend</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('friends.store') }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="number" name="friend_id" class="form-control"
                       placeholder="Enter user ID">
                <button class="btn btn-primary">Send Request</button>
            </form>
        </div>
    </div>
</div>
@endsection