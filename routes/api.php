<?php

use App\Http\Controllers\API\AttributeController;
use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\BillDetailController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\OrderDetailController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VariantController;
use App\Http\Controllers\API\VoucherController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Product
Route::apiResource('products', ProductController::class);

//Variant
Route::apiResource('variants', VariantController::class);

//Attribute
Route::apiResource('attributes', AttributeController::class);

//Bill
Route::apiResource('bills', BillController::class);

//BillDetail
Route::apiResource('bill-details', BillDetailController::class);

//Order
Route::apiResource('orders', OrderController::class);

//OrderDetail
Route::apiResource('order-details', OrderDetailController::class);

//Category
Route::apiResource('categories', CategoryController::class);



//User

Route::apiResource('users', UserController::class);


Route::post('register', [\App\Http\Controllers\API\AuthController::class, 'register'])->name('register');
Route::post('login', [\App\Http\Controllers\API\AuthController::class, 'login'])->name('login');


Route::group(['middleware' => 'auth:sanctum'], function () {

    // Route::get('/profile', function (Request $request) {
    //     return auth()->users();
    // });

    Route::get('logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])->name('logout');
});
