@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-users"></i> Followers ({{ $followers->count() }})</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($followers as $follower)
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center p-3 border rounded">
                        <div class="avatar me-3">
                            {{ substr($follower->name, 0, 1) }}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $follower->name }}</h6>
                            <small class="text-muted">{{ $follower->email }}</small>
                        </div>
                        <a href="/follow-user/{{ $follower->id }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-user-plus"></i> Follow Back
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <p>No followers yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection