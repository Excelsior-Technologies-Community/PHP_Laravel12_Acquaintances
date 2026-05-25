@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-clock"></i> Pending Friend Requests</h5>
    </div>
    <div class="card-body">
        @forelse($pending_requests as $request)
            <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-3">
                <div>
                    <div class="avatar d-inline-block me-2">
                        {{ substr($request->name, 0, 1) }}
                    </div>
                    <strong>{{ $request->name }}</strong>
                    <small class="text-muted d-block">{{ $request->email }}</small>
                </div>
                <div>
                    <a href="/accept-request/{{ $request->id }}" class="btn btn-sm btn-success">
                        <i class="fas fa-check"></i> Accept
                    </a>
                    <a href="/reject-request/{{ $request->id }}" class="btn btn-sm btn-danger">
                        <i class="fas fa-times"></i> Reject
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-clock fa-3x mb-3"></i>
                <p>No pending friend requests.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection