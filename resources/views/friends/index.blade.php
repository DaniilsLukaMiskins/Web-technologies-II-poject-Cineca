@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4" style="color:#F0F465;">{{ __('messages.friends') }}</h1>

    @if($pending->isNotEmpty())
    <h2 class="mb-3" style="color:#9CEC5B;">{{ __('messages.friend_requests') }}</h2>
    @foreach($pending as $request)
    <div class="card mb-2">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span>{{ $request->user->username }} wants to be your friend</span>
            <div class="d-flex gap-2">
                <form action="{{ route('friends.update', $request) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="accepted">
                    <button class="btn btn-sm" style="background-color:#9CEC5B; color:#000; font-weight:bold;">
                        {{ __('messages.accept') }}
                    </button>
                </form>
                <form action="{{ route('friends.update', $request) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <button class="btn btn-sm btn-danger">{{ __('messages.reject') }}</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    <h2 class="mt-4 mb-3" style="color:#F0F465;">{{ __('messages.my_friends') }}</h2>

    @if($friends->isEmpty())
        <p>You have no friends yet.</p>
    @endif

    @foreach($friends as $friend)
    <div class="card mb-2">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span>{{ $friend->friend->username }}</span>
            <form action="{{ route('friends.destroy', $friend) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">{{ __('messages.remove') }}</button>
            </form>
        </div>
    </div>
    @endforeach

    <h2 class="mt-4 mb-3" style="color:#F0F465;">{{ __('messages.add_friend') }}</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('friends.store') }}" method="POST" class="d-flex flex-column gap-2">
                @csrf
                <div class="d-flex gap-2">
                    <input type="text" name="username" class="form-control"
                        placeholder="{{ __('messages.enter_username') }}"
                        value="{{ old('username') }}">
                    <button class="btn btn-primary">{{ __('messages.send_request') }}</button>
                </div>
                @error('username')
                    <small class="text-warning">{{ $message }}</small>
                @enderror
            </form>
        </div>
    </div>
</div>
@endsection