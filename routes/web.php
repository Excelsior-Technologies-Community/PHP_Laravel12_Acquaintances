<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcquaintanceController;

Route::get('/', function () {
    return view('welcome');
});

// Core Features
Route::get('/send-request', [AcquaintanceController::class, 'sendRequest']);
Route::get('/accept-request', [AcquaintanceController::class, 'acceptRequest']);
Route::get('/follow-user', [AcquaintanceController::class, 'followUser']);
Route::get('/like-user', [AcquaintanceController::class, 'likeUser']);
Route::get('/check-friend', [AcquaintanceController::class, 'checkFriend']);

// EXTRA FEATURES
Route::get('/friend-list', [AcquaintanceController::class, 'friendList']);
Route::get('/following-list', [AcquaintanceController::class, 'followingList']);
Route::get('/user-status/{id}', [AcquaintanceController::class, 'userStatus']);