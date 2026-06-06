@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4" style="color:#F0F465;">Admin Panel</h1>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('admin.index') }}" class="btn btn-primary">Users</a>
        <a href="{{ route('admin.log') }}" class="btn btn-outline-light">Audit Log</a>
    </div>

    <div class="card" style="background-color:#6184D8;">
        <div class="card-body">
            <table class="table table-striped mb-0" style="color:white;">
                <thead>
                    <tr style="border-color: rgba(255,255,255,0.2);">
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Change Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="border-color: rgba(255,255,255,0.2);">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge"
                                  style="background-color:#F0F465; color:#000;">
                                {{ $user->role->name }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.changeRole', $user) }}"
                                  method="POST" class="d-flex gap-2">
                                @csrf
                                <select name="role_id"
                                        class="form-select form-select-sm"
                                        style="background-color:#533A71; color:white;
                                               border-color:#50C5B7;">
                                    @foreach(\App\Models\Role::all() as $role)
                                    <option value="{{ $role->id }}"
                                        {{ $user->role_id === $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-primary">Save</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection