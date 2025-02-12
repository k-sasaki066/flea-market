<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\StripeWebhookController;

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
Route::get('/item/:{item_id}', [ItemController::class, 'getDetail']);

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/mypage/profile', [UserController::class, 'getProfile']);
    Route::post('/mypage/profile', [UserController::class, 'postProfile']);
    Route::get('/mypage', [UserController::class, 'getMypage']);
    Route::post('/like/:{item_id}', [FavoriteController::class, 'create']);
    Route::delete('/unlike/:{item_id}', [FavoriteController::class, 'delete']);
    Route::post('/comment/:{item_id}', [ItemController::class, 'postComment']);
    Route::get('/sell', [UserController::class, 'getSell']);
    Route::post('/sell', [UserController::class, 'postSell']);
    Route::get('/purchase/:{item_id}', [PurchaseController::class, 'getPurchase']);
    Route::post('/purchase/:{item_id}', [PurchaseController::class, 'postPurchase'])->name('stripe.session');
    Route::get('/purchase/address/:{item_id}', [PurchaseController::class, 'getAddress']);
    Route::post('/purchase/address/:{item_id}', [PurchaseController::class, 'postAddress']);

    Route::get('/success', [PurchaseController::class, 'success'])->name('stripe.success');
    Route::get('/cancel', [PurchaseController::class, 'cancel'])->name('stripe.cancel');
});

Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook']);