@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-users"></i> Mutual Friends with {{ $other_user->name }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($mutual_friends as $friend)
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center p-3 border rounded">
                        <div class="avatar me-3">
                            {{ substr($friend->name, 0, 1) }}
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $friend->name }}</h6>
                            <small class="text-muted">{{ $friend->email }}</small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <p>No mutual friends.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection