<?php

use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use App\Models\Opening;
use App\Models\Additional;
use App\Models\VendorCode;
use App\Models\VendorAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\VendorCodeController;

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
Route::get('/vendors', [VendorCodeController::class, 'index'])->middleware('auth:sanctum');

Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/user/history', [UserController::class, 'history'])->middleware('auth:sanctum');
Route::put('/user', [UserController::class, 'update'])->middleware('auth:sanctum');

Route::post('/order', [OrderController::class, 'store'])->middleware('auth:sanctum');
Route::get('/pdf/{userID}/{orderID}', function (string $userID, string $orderID) {
    $user = User::find($userID);
    $order = Order::find($orderID);
    $openings = Opening::all()->where('order_id', '=', $order['id']);
    $additionals = Additional::all()->where('order_id', '=', $order['id']);
    $vendorsAmount = VendorAmount::all()->where('order_id', '=', $order['id']);
    $vendors = VendorCode::all();
    $items = Item::all();
    return json_encode([
        'user' => $user,
        'order' => $order,
        'openings' => $openings,
        'additionals' => $additionals,
        'vendorsAmount' => $vendorsAmount,
        'vendors' => $vendors,
        'items' => $items,
    ]);
});

Route::get('/convert-image', [ImageController::class, 'convertImage']);