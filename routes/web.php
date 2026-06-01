<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcquaintanceController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// ========== CORE FEATURES ==========
Route::get('/send-request/{id}', [AcquaintanceController::class, 'sendRequest']);
Route::get('/accept-request/{id}', [AcquaintanceController::class, 'acceptRequest']);
Route::get('/reject-request/{id}', [AcquaintanceController::class, 'rejectRequest']);
Route::get('/follow-user/{id}', [AcquaintanceController::class, 'followUser']);
Route::get('/unfollow-user/{id}', [AcquaintanceController::class, 'unfollowUser']);
Route::get('/like-user/{id}', [AcquaintanceController::class, 'likeUser']);
Route::get('/unlike-user/{id}', [AcquaintanceController::class, 'unlikeUser']);

// ========== EXTRA FEATURES ==========
Route::get('/dashboard', [AcquaintanceController::class, 'dashboard'])->name('dashboard');
Route::get('/friend-list', [AcquaintanceController::class, 'friendList']);
Route::get('/following-list', [AcquaintanceController::class, 'followingList']);
Route::get('/follower-list', [AcquaintanceController::class, 'followerList']);
Route::get('/mutual-friends/{id}', [AcquaintanceController::class, 'mutualFriends']);
Route::get('/friend-suggestions', [AcquaintanceController::class, 'friendSuggestions']);
Route::get('/pending-requests', [AcquaintanceController::class, 'pendingRequests']);
Route::get('/block-user/{id}', [AcquaintanceController::class, 'blockUser']);
Route::get('/unblock-user/{id}', [AcquaintanceController::class, 'unblockUser']);
Route::get('/remove-friend/{id}', [AcquaintanceController::class, 'removeFriend']);
Route::get('/user-status/{id}', [AcquaintanceController::class, 'userStatus']);
Route::get('/all-users', [AcquaintanceController::class, 'allUsers']);
Route::get('/switch-user/{id}', [AcquaintanceController::class, 'switchUser']);
Route::get('/current-user', [AcquaintanceController::class, 'currentUser']);

Route::get('/rate-user/{id}', [AcquaintanceController::class, 'rateUser']);
Route::get('/manage-groups', [AcquaintanceController::class, 'manageGroups']);
Route::get('/add-friend-group', [AcquaintanceController::class, 'addFriendToGroup']);
Route::get('/remove-friend-group', [AcquaintanceController::class, 'removeFriendFromGroup']);