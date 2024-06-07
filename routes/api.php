<?php

use App\Http\Controllers\API\AttributeController;
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

//Voucher
Route::apiResource('voucher', VoucherController::class);

//Category
Route::get('list_category', [\App\Http\Controllers\API\CategoryController::class, 'index'])->name('list.category');
Route::get('category/{id}', [\App\Http\Controllers\API\CategoryController::class, 'show'])->name('show.category');
Route::post('category_post', [\App\Http\Controllers\API\CategoryController::class, 'store'])->name('store.category');
Route::post('category_update/{id}', [\App\Http\Controllers\API\CategoryController::class, 'update'])->name('update.category');
Route::delete('category_delete/{id}', [\App\Http\Controllers\API\CategoryController::class, 'destroy'])->name('destroy.category');

//User

Route::apiResource('users', UserController::class);


Route::post('register', [\App\Http\Controllers\API\AuthController::class, 'register'])->name('register');
Route::post('login', [\App\Http\Controllers\API\AuthController::class, 'login'])->name('login');


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/profile', function (Request $request) {
        return auth()->user();
    });

    Route::get('logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])->name('logout');
});
