@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-globe"></i> All Users</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usersData as $data)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2" style="width: 35px; height: 35px; font-size: 14px;">
                                        {{ substr($data['user']->name, 0, 1) }}
                                    </div>
                                    <strong>{{ $data['user']->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $data['user']->email }}</td>
                            <td>
                                @if($data['is_friend'])
                                    <span class="badge bg-success">Friend</span>
                                @elseif($data['friend_request_sent'])
                                    <span class="badge bg-warning">Request Sent</span>
                                @elseif($data['friend_request_received'])
                                    <span class="badge bg-info">Request Received</span>
                                @elseif($data['is_blocked'])
                                    <span class="badge bg-danger">Blocked</span>
                                @else
                                    <span class="badge bg-secondary">Not Connected</span>
                                @endif
                                
                                @if($data['is_following'])
                                    <span class="badge bg-primary">Following</span>
                                @endif
                                
                                @if($data['is_liked'])
                                    <span class="badge bg-danger">Liked</span>
                                @endif
                            </td>
                            <td>
                                @if($data['is_blocked'])
                                    <a href="/unblock-user/{{ $data['user']->id }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-ban"></i> Unblock
                                    </a>
                                @elseif($data['is_friend'])
                                    <a href="/remove-friend/{{ $data['user']->id }}" class="btn btn-sm btn-danger" onclick="return confirm('Remove friend?')">
                                        <i class="fas fa-user-minus"></i> Remove Friend
                                    </a>
                                @elseif($data['friend_request_sent'])
                                    <span class="text-muted">Request Pending</span>
                                @elseif($data['friend_request_received'])
                                    <a href="/accept-request/{{ $data['user']->id }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Accept
                                    </a>
                                    <a href="/reject-request/{{ $data['user']->id }}" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i> Reject
                                    </a>
                                @else
                                    <a href="/send-request/{{ $data['user']->id }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-user-plus"></i> Add Friend
                                    </a>
                                @endif
                                
                                @if(!$data['is_following'] && !$data['is_blocked'])
                                    <a href="/follow-user/{{ $data['user']->id }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-user-plus"></i> Follow
                                    </a>
                                @elseif($data['is_following'])
                                    <a href="/unfollow-user/{{ $data['user']->id }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-user-minus"></i> Unfollow
                                    </a>
                                @endif
                                
                                @if(!$data['is_liked'] && !$data['is_blocked'])
                                    <a href="/like-user/{{ $data['user']->id }}" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-heart"></i> Like
                                    </a>
                                @elseif($data['is_liked'])
                                    <a href="/unlike-user/{{ $data['user']->id }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-heart-broken"></i> Unlike
                                    </a>
                                @endif
                                
                                @if(!$data['is_blocked'] && !$data['is_friend'])
                                    <a href="/block-user/{{ $data['user']->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Block this user?')">
                                        <i class="fas fa-ban"></i> Block
                                    </a>
                                @endif
                                
                                <a href="/mutual-friends/{{ $data['user']->id }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-users"></i> Mutual
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection