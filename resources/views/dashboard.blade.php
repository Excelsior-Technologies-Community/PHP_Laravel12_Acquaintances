@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                {{ substr($current_user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="mb-0">Welcome, {{ $current_user->name }}!</h3>
                                <p class="text-muted mb-0">{{ $current_user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-flex align-items-center justify-content-end gap-3">
                            <a href="/manage-groups" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-folder-plus"></i> Manage Groups
                            </a>
                            <div class="user-switch">
                                <label class="me-2 mb-0">Switch User:</label>
                                @foreach([1,2,3,4,5] as $uid)
                                    @php $u = \App\Models\User::find($uid); @endphp
                                    @if($u)
                                        <a href="/switch-user/{{ $uid }}" class="btn btn-sm {{ $current_user_id == $uid ? 'btn-primary' : 'btn-outline-secondary' }} me-1">
                                            {{ $u->name }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $friends->count() }}</div>
            <div class="stats-label">Friends</div>
            <a href="/friend-list" class="btn btn-sm btn-link">View All</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $followings->count() }}</div>
            <div class="stats-label">Following</div>
            <a href="/following-list" class="btn btn-sm btn-link">View All</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $followers->count() }}</div>
            <div class="stats-label">Followers</div>
            <a href="/follower-list" class="btn btn-sm btn-link">View All</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $pending_friend_requests->count() }}</div>
            <div class="stats-label">Pending Requests</div>
            <a href="/pending-requests" class="btn btn-sm btn-link">View All</a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-user-plus"></i> Pending Friend Requests</h5>
            </div>
            <div class="card-body">
                @forelse($pending_friend_requests as $request)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
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
                    <p class="text-muted text-center">No pending friend requests</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Advanced Friend Suggestions</h5>
            </div>
            <div class="card-body">
                @forelse($friend_suggestions as $suggestion)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>{{ $suggestion->name }}</strong>
                            <span class="badge bg-info ms-2">{{ $suggestion->mutual_count }} Mutual Friends</span>
                            <small class="text-muted d-block">{{ $suggestion->email }}</small>
                        </div>
                        <a href="/send-request/{{ $suggestion->id }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-user-plus"></i> Add Friend
                        </a>
                    </div>
                @empty
                    <p class="text-muted text-center">No friend suggestions</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> Your Friends ({{ $friends->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($friends as $friend)
                        <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-2" style="width: 40px; height: 40px; font-size: 16px;">
                                    {{ substr($friend->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $friend->name }}</strong>
                                    <div>
                                        <a href="/mutual-friends/{{ $friend->id }}" class="small">Mutual Friends</a>
                                        <a href="/remove-friend/{{ $friend->id }}" class="small text-danger ms-2" onclick="return confirm('Remove friend?')">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted">No friends yet</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection