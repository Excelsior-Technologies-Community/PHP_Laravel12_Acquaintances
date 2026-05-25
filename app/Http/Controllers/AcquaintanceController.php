<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AcquaintanceController extends Controller
{
    // Track current user for testing (default: 1)
    protected $currentUserId = 1;

    /**
     * Get current user
     */
    private function getCurrentUser()
    {
        return User::find($this->currentUserId);
    }

    /**
     * Switch user for testing
     */
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

    /**
     * Get current user info
     */
    public function currentUser()
    {
        $user = $this->getCurrentUser();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Dashboard with all data
     */
    public function dashboard()
    {
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'current_user' => $currentUser,
            'friends' => $currentUser->getFriends(),
            'followings' => $currentUser->getFollowings(),
            'followers' => $currentUser->getFollowers(),
            'pending_friend_requests' => $currentUser->getFriendRequests(),
            'sent_requests' => $currentUser->getSentFriendRequests(),
            'blocked_users' => $currentUser->getBlockedFriendships(),
            'friend_suggestions' => $this->getFriendSuggestionsData(),
            'all_users' => User::where('id', '!=', $currentUser->id)->get(),
            'current_user_id' => $this->currentUserId,
        ];
        
        return view('dashboard', $data);
    }

    /**
     * Get all users for interaction
     */
    public function allUsers()
    {
        $currentUser = $this->getCurrentUser();
        $users = User::where('id', '!=', $currentUser->id)->get();
        
        $usersData = [];
        foreach ($users as $user) {
            $usersData[] = [
                'user' => $user,
                'is_friend' => $currentUser->isFriendWith($user),
                'friend_status' => $currentUser->getFriendshipStatus($user),
                'is_following' => $currentUser->isFollowing($user),
                'is_liked' => $currentUser->hasLiked($user),
                'is_blocked' => $currentUser->isBlocked($user),
                'friend_request_sent' => $currentUser->hasSentFriendRequestTo($user),
                'friend_request_received' => $currentUser->hasReceivedFriendRequestFrom($user),
            ];
        }
        
        return view('all-users', ['usersData' => $usersData, 'current_user' => $currentUser]);
    }

    // ========== FRIEND REQUEST METHODS ==========
    
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

    // ========== FOLLOW METHODS ==========
    
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

    // ========== LIKE METHODS ==========
    
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

    // ========== BLOCK METHODS ==========
    
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

    // ========== LIST METHODS ==========
    
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
            'followings' => $currentUser->getFollowings(),
            'current_user' => $currentUser
        ]);
    }
    
    public function followerList()
    {
        $currentUser = $this->getCurrentUser();
        return view('followers-list', [
            'followers' => $currentUser->getFollowers(),
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
        
        return response()->json([
            'user' => $user2->name,
            'is_friend' => $user1->isFriendWith($user2),
            'is_following' => $user1->isFollowing($user2),
            'is_liked' => $user1->hasLiked($user2),
            'is_blocked' => $user1->isBlocked($user2),
            'friend_request_status' => $user1->getFriendshipStatus($user2),
        ]);
    }
    
    /**
     * Get friend suggestions based on followers of followed users
     */
    private function getFriendSuggestionsData()
    {
        $currentUser = $this->getCurrentUser();
        $suggestions = collect();
        
        // Get users that current user is following
        $followings = $currentUser->getFollowings();
        
        foreach ($followings as $following) {
            // Get followers of each followed user
            $followersOfFollowing = $following->getFollowers();
            foreach ($followersOfFollowing as $potentialFriend) {
                // Exclude current user and existing friends
                if ($potentialFriend->id != $currentUser->id && 
                    !$currentUser->isFriendWith($potentialFriend) &&
                    !$currentUser->hasSentFriendRequestTo($potentialFriend)) {
                    $suggestions->push($potentialFriend);
                }
            }
        }
        
        // Remove duplicates and limit to 10
        return $suggestions->unique('id')->take(10)->values();
    }
}