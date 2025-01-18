<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegisteredUserController;

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

// Route::get('/login', function () {
//     return view('auth.login');
// });

Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/', [ItemController::class, 'index']);
Route::get('/search', [ItemController::class, 'searchItem']);
Route::get('/item/:{item_id}', [ItemController::class, 'getDetail']);

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ItemController::class, 'getProfile']);
});