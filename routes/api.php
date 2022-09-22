<?php

use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/subscribe/change', [\App\Http\Controllers\SubscriptionController::class, 'webhook']);
Route::post('/cancel-subscription', [\App\Http\Controllers\SubscriptionController::class, 'cancelSubscription']);

Route::post('/login',[UserController::class,'userLogin']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/profile-details', [UserController::class, 'userDetails']);
});


