<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AcquaintanceController extends Controller
{
    protected $currentUserId = 1;

    private function getCurrentUser()
    {
        $user = User::find($this->currentUserId);
        if (!$user) {
            $user = User::first();
        }
        return $user;
    }

    public function switchUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $this->currentUserId = $id;
            session(['current_user_id' => $id]);
            return redirect()->back()->with('success', 'Switched to: ' . $user->name);
        }
        return redirect()->back()->with('error', 'User not found');
    }

    public function currentUser()
    {
        $user = $this->getCurrentUser();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function dashboard()
    {
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'current_user' => $currentUser,
            'friends' => $currentUser->getFriends(),
            'followings' => $currentUser->followings,
            'followers' => $currentUser->followers,
            'pending_friend_requests' => $currentUser->getFriendRequests(),
            'sent_requests' => collect(),
            'blocked_users' => $currentUser->getBlockedFriendships(),
            'friend_suggestions' => $this->getFriendSuggestionsData(),
            'all_users' => User::where('id', '!=', $currentUser->id)->get(),
            'current_user_id' => $this->currentUserId,
        ];
        
        return view('dashboard', $data);
    }

    public function allUsers()
    {
        $currentUser = $this->getCurrentUser();
        $users = User::where('id', '!=', $currentUser->id)->get();
        $ratedUsers = $currentUser->ratingsTo(User::class)->get()->keyBy('id');
        
        $usersData = [];
        foreach ($users as $user) {
            $friendship = $currentUser->getFriendship($user);
            $friendStatus = $friendship ? $friendship->status : 'none';
            $ratingVal = $ratedUsers->has($user->id) ? $ratedUsers->get($user->id)->pivot->relation_value : 0;

            $usersData[] = [
                'user' => $user,
                'is_friend' => $currentUser->isFriendWith($user),
                'friend_status' => $friendStatus,
                'is_following' => $currentUser->isFollowing($user),
                'is_liked' => $currentUser->hasLiked($user),
                'is_blocked' => $currentUser->hasBlocked($user),
                'friend_request_sent' => $currentUser->hasSentFriendRequestTo($user),
                'friend_request_received' => $currentUser->hasFriendRequestFrom($user),
                'rating' => $ratingVal,
            ];
        }
        
        return view('all-users', ['usersData' => $usersData, 'current_user' => $currentUser]);
    }

    public function rateUser(Request $request, $id)
    {
        $currentUser = $this->getCurrentUser();
        $targetUser = User::findOrFail($id);
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $currentUser->rate($targetUser, $request->rating);

        return redirect()->back()->with('success', 'User rated successfully!');
    }

    public function manageGroups()
    {
        $currentUser = $this->getCurrentUser();
        $friends = $currentUser->getFriends();
        
        $availableGroups = array_keys(config('acquaintances.friendships_groups', [
            'acquaintances' => 0,
            'close_friends' => 1,
            'family' => 2
        ]));

        $groupedFriends = [];
        foreach ($availableGroups as $group) {
            $groupedFriends[$group] = $currentUser->getFriends(0, $group);
        }
        
        return view('manage-groups', compact('currentUser', 'friends', 'availableGroups', 'groupedFriends'));
    }

    public function addFriendToGroup(Request $request)
    {
        $currentUser = $this->getCurrentUser();
        $request->validate([
            'friend_id' => 'required|integer',
            'group_name' => 'required|string|max:50'
        ]);

        $friend = User::findOrFail($request->friend_id);
        $currentUser->groupFriend($friend, $request->group_name);

        return redirect()->back()->with('success', 'Friend added to group successfully!');
    }

    public function removeFriendFromGroup(Request $request)
    {
        $currentUser = $this->getCurrentUser();
        $request->validate([
            'friend_id' => 'required|integer',
            'group_name' => 'required|string|max:50'
        ]);

        $friend = User::findOrFail($request->friend_id);
        $currentUser->ungroupFriend($friend, $request->group_name);

        return redirect()->back()->with('success', 'Friend removed from group!');
    }

    public function sendRequest($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $user1->befriend($user2);
        return redirect()->back()->with('success', 'Friend request sent to ' . $user2->name);
    }
    
    public function acceptRequest($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $user1->acceptFriendRequest($user2);
        return redirect()->back()->with('success', 'Friend request accepted from ' . $user2->name);
    }
    
    public function rejectRequest($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $user1->denyFriendRequest($user2);
        return redirect()->back()->with('success', 'Friend request rejected from ' . $user2->name);
    }
    
    public function removeFriend($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $user1->unfriend($user2);
        return redirect()->back()->with('success', $user2->name . ' removed from friends');
    }

    public function followUser($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $user1->follow($user2);
        return redirect()->back()->with('success', 'Now following ' . $user2->name);
    }
    
    public function unfollowUser($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $user1->unfollow($user2);
        return redirect()->back()->with('success', 'Unfollowed ' . $user2->name);
    }

    public function likeUser($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $user1->like($user2);
        return redirect()->back()->with('success', 'Liked ' . $user2->name);
    }
    
    public function unlikeUser($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $user1->unlike($user2);
        return redirect()->back()->with('success', 'Unliked ' . $user2->name);
    }

    public function blockUser($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $user1->blockFriend($user2);
        return redirect()->back()->with('success', 'Blocked ' . $user2->name);
    }
    
    public function unblockUser($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $user1->unblockFriend($user2);
        return redirect()->back()->with('success', 'Unblocked ' . $user2->name);
    }

    public function friendList()
    {
        $currentUser = $this->getCurrentUser();
        return view('friends-list', [
            'friends' => $currentUser->getFriends(),
            'current_user' => $currentUser
        ]);
    }
    
    public function followingList()
    {
        $currentUser = $this->getCurrentUser();
        return view('following-list', [
            'followings' => $currentUser->followings,
            'current_user' => $currentUser
        ]);
    }
    
    public function followerList()
    {
        $currentUser = $this->getCurrentUser();
        return view('followers-list', [
            'followers' => $currentUser->followers,
            'current_user' => $currentUser
        ]);
    }
    
    public function mutualFriends($id)
    {
        $currentUser = $this->getCurrentUser();
        $otherUser = User::find($id);
        
        if (!$otherUser) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        $mutualFriends = $currentUser->getMutualFriends($otherUser);
        
        return view('mutual-friends', [
            'mutual_friends' => $mutualFriends,
            'other_user' => $otherUser,
            'current_user' => $currentUser
        ]);
    }
    
    public function friendSuggestions()
    {
        $suggestions = $this->getFriendSuggestionsData();
        return view('friend-suggestions', [
            'suggestions' => $suggestions,
            'current_user' => $this->getCurrentUser()
        ]);
    }
    
    public function pendingRequests()
    {
        $currentUser = $this->getCurrentUser();
        return view('pending-requests', [
            'pending_requests' => $currentUser->getFriendRequests(),
            'current_user' => $currentUser
        ]);
    }
    
    public function userStatus($id)
    {
        $user1 = $this->getCurrentUser();
        $user2 = User::find($id);
        
        if (!$user2) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $friendship = $user1->getFriendship($user2);
        
        return response()->json([
            'user' => $user2->name,
            'is_friend' => $user1->isFriendWith($user2),
            'is_following' => $user1->isFollowing($user2),
            'is_liked' => $user1->hasLiked($user2),
            'is_blocked' => $user1->hasBlocked($user2),
            'friend_request_status' => $friendship ? $friendship->status : 'none',
        ]);
    }
    
    private function getFriendSuggestionsData()
    {
        $currentUser = $this->getCurrentUser();
        $allUsers = User::where('id', '!=', $currentUser->id)->get();
        $suggestions = [];

        foreach ($allUsers as $otherUser) {
            if ($currentUser->isFriendWith($otherUser) || 
                $currentUser->hasSentFriendRequestTo($otherUser) || 
                $currentUser->hasFriendRequestFrom($otherUser) || 
                $currentUser->hasBlocked($otherUser)) {
                continue;
            }

            $mutualFriends = $currentUser->getMutualFriends($otherUser);
            $otherUser->mutual_count = count($mutualFriends);
            $suggestions[] = $otherUser;
        }

        usort($suggestions, function($a, $b) {
            return $b->mutual_count <=> $a->mutual_count;
        });

        return collect($suggestions)->take(5);
    }
}