@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4" style="color:#F0F465;">Audit Log</h1>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('admin.index') }}" class="btn btn-outline-light">Users</a>
        <a href="{{ route('admin.log') }}" class="btn btn-primary">Audit Log</a>
    </div>

    <div class="card" style="background-color:#6184D8;">
        <div class="card-body">
            <table class="table table-striped mb-0" style="color:white;">
                <thead>
                    <tr style="border-color: rgba(255,255,255,0.2);">
                        <th>Date</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Entity</th>
                        <th>ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr style="border-color: rgba(255,255,255,0.2);">
                        <td>{{ $log->created_at }}</td>
                        <td>{{ $log->user->username }}</td>
                        <td>
                            <span class="badge"
                                  style="background-color:#50C5B7; color:#000;">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td>{{ $log->entity_type }}</td>
                        <td>{{ $log->entity_id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection