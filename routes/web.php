<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/subscribe', [SubscriptionController::class, 'index']);
Route::get('/allPlans', [SubscriptionController::class, 'allPlans']);
Route::post('/subscribe', [SubscriptionController::class, 'store']);
Route::get('/userProfile', [UserController::class,'myProfile']);

Auth::routes();

Route::post('/insertProduct', [ProductsController::class,'create']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/message', [MessageController::class, 'index']);
Route::get('/all-users', [UserController::class, 'showUsers']);
Route::post('/message/open', [MessageController::class, 'openChat']);
Route::post('/send/message', [MessageController::class, 'sendMessage']);

Route::middleware(['user.subscription'])->group(function () {
    Route::get('/products', [ProductsController::class, 'showAllProducts']);
});
