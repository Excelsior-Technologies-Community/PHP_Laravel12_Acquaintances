@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Following ({{ $followings->count() }})</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($followings as $following)
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center p-3 border rounded">
                        <div class="avatar me-3">
                            {{ substr($following->name, 0, 1) }}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $following->name }}</h6>
                            <small class="text-muted">{{ $following->email }}</small>
                        </div>
                        <a href="/unfollow-user/{{ $following->id }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-user-minus"></i> Unfollow
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-user-plus fa-3x mb-3"></i>
                    <p>Not following anyone yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection