@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-user-friends"></i> Your Friends ({{ $friends->count() }})</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($friends as $friend)
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center p-3 border rounded">
                        <div class="avatar me-3">
                            {{ substr($friend->name, 0, 1) }}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $friend->name }}</h6>
                            <small class="text-muted">{{ $friend->email }}</small>
                        </div>
                        <div>
                            <a href="/mutual-friends/{{ $friend->id }}" class="btn btn-sm btn-info" title="Mutual Friends">
                                <i class="fas fa-users"></i>
                            </a>
                            <a href="/remove-friend/{{ $friend->id }}" class="btn btn-sm btn-danger" onclick="return confirm('Remove friend?')" title="Remove Friend">
                                <i class="fas fa-user-minus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-user-friends fa-3x mb-3"></i>
                    <p>No friends yet. Start connecting!</p>
                    <a href="/all-users" class="btn btn-primary">Find Friends</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection