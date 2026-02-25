<?php

namespace App\Http\Controllers;

use App\Models\User;

class AcquaintanceController extends Controller
{

    // Send Friend Request
    public function sendRequest()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->befriend($user2);

        return "Friend Request Sent";
    }

    // Accept Friend Request
    public function acceptRequest()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user2->acceptFriendRequest($user1);

        return "Friend Request Accepted";
    }

    // Follow User
    public function followUser()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->follow($user2);

        return "User Followed";
    }

    // Like User
    public function likeUser()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->like($user2);

        return "User Liked";
    }

    // Check Friend Status
    public function checkFriend()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        if ($user1->isFriendWith($user2)) {
            return "They are Friends";
        }

        return "Not Friends";
    }

}