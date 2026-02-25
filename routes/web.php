<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcquaintanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send-request', [AcquaintanceController::class, 'sendRequest']);

Route::get('/accept-request', [AcquaintanceController::class, 'acceptRequest']);

Route::get('/follow-user', [AcquaintanceController::class, 'followUser']);

Route::get('/like-user', [AcquaintanceController::class, 'likeUser']);

Route::get('/check-friend', [AcquaintanceController::class, 'checkFriend']);