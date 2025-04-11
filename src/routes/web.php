<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\EmailVerificationNotificationController;
use App\Http\Controllers\MessageController;

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

Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/', [ItemController::class, 'index']);
Route::get('/search', [ItemController::class, 'searchItem']);
Route::get('/item/{item}', [ItemController::class, 'getDetail']);

Route::middleware('auth','throttle:6,1')->group(function () {
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'resend'])->name('verification.send');
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/mypage/profile', [UserController::class, 'getProfile']);
    Route::post('/mypage/profile', [UserController::class, 'postProfile']);
    Route::get('/mypage', [UserController::class, 'getMypage']);

    Route::post('/like/{item}', [FavoriteController::class, 'createFavorite']);
    Route::delete('/unlike/{item}', [FavoriteController::class, 'deleteFavorite']);
    Route::post('/comment/{item}', [ItemController::class, 'postComment']);

    Route::get('/sell', [UserController::class, 'getSell']);
    Route::post('/sell', [UserController::class, 'postSell']);
    Route::get('/api/brands', [UserController::class, 'getBrandName']);

    Route::get('/purchase/{item}', [PurchaseController::class, 'getPurchase']);
    Route::post('/purchase/{item}', [PurchaseController::class, 'postPurchase'])->name('stripe.session');

    Route::get('/purchase/address/{item}', [PurchaseController::class, 'getAddress']);
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'postAddress']);

    Route::get('/success', [PurchaseController::class, 'success'])->name('stripe.success');
    Route::get('/cancel', [PurchaseController::class, 'cancel'])->name('stripe.cancel');

    Route::get('/transaction/{transaction}', [UserController::class, 'getTransaction']);
    Route::post('/transaction/{transaction}', [MessageController::class, 'sendMessage']);

    Route::put('/message/{message}', [MessageController::class, 'update']);
});

Route::get('/stripe/session-status', [PurchaseController::class, 'getSessionStatus']);
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook']);