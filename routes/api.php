<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'status' => 'success',
        'code' => 200
    ]);
});

// Route::post('register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/items', [ItemController::class, 'index'])->middleware('auth:sanctum');

Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');
// Route::get('/user/history', []);
Route::put('/user', [UserController::class, 'update'])->middleware('auth:sanctum');

Route::post('/order', [OrderController::class, 'store'])->middleware('auth:sanctum');

Route::get('/test', [OrderController::class, 'index']);