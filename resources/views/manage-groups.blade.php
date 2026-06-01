@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-folder-plus"></i> Manage Friendship Groups</h5>
    </div>
    <div class="card-body">
        <form action="/add-friend-group" method="GET" class="row g-3 mb-5">
            <div class="col-md-5">
                <label class="form-label">Select Friend</label>
                <select name="friend_id" class="form-select" required>
                    <option value="">-- Choose Friend --</option>
                    @foreach($friends as $friend)
                        <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Select Group</label>
                <select name="group_name" class="form-select" required>
                    <option value="">-- Choose Group --</option>
                    @foreach($availableGroups as $group)
                        <option value="{{ $group }}">{{ ucwords(str_replace('_', ' ', $group)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Add to Group</button>
            </div>
        </form>

        <h6 class="border-bottom pb-2 mb-3">Your Friends & Their Groups</h6>
        <div class="row">
            @foreach($friends as $friend)
                <div class="col-md-4 mb-3">
                    <div class="p-3 border rounded bg-light">
                        <strong>{{ $friend->name }}</strong>
                        <div class="mt-2">
                            @foreach($availableGroups as $group)
                                @if(isset($groupedFriends[$group]) && $groupedFriends[$group]->contains('id', $friend->id))
                                    <span class="badge bg-secondary me-1">
                                        {{ ucwords(str_replace('_', ' ', $group)) }}
                                        <a href="/remove-friend-group?friend_id={{ $friend->id }}&group_name={{ $group }}" class="text-white ms-1 text-decoration-none">×</a>
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection