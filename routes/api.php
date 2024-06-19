<?php

use App\Http\Controllers\API\AttributeController;
use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\BillDetailController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ColorController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\OrderDetailController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\SizeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VariantController;
use App\Models\Order;
use App\Models\OrderDetail;
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

Route::middleware(['auth:sanctum', 'checkAdmin'])->group(function () {

    // //Categories
    // Route::apiResource("categories", \App\Http\Controllers\API\CategoryController::class);
    // Route::get('categories/{id}/total-products', [\App\Http\Controllers\API\CategoryController::class, 'totalProducts']);

    // //Product
    // Route::apiResource("products", \App\Http\Controllers\API\ProductController::class);
    // Route::apiResource("variants", \App\Http\Controllers\API\VariantController::class);
    // Route::apiResource("attributes", \App\Http\Controllers\API\AttributeController::class);



    // //User

    // Route::apiResource('users', UserController::class);
});

//Categories
Route::apiResource("categories", \App\Http\Controllers\API\CategoryController::class);
Route::get('categories/{id}/total-products', [\App\Http\Controllers\API\CategoryController::class, 'totalProducts']);

//Product
Route::apiResource("products", \App\Http\Controllers\API\ProductController::class);
Route::apiResource("variants", \App\Http\Controllers\API\VariantController::class);
Route::apiResource("attributes", \App\Http\Controllers\API\AttributeController::class);
Route::get("get-by-sale", [ProductController::class, 'getBySale']);
Route::get("get-by-sale/{id}", [ProductController::class, 'getProductBySaleId']);


//User

Route::apiResource('users', UserController::class);


//Bill
Route::apiResource('bills', BillController::class);
Route::get('bills-with-billDetail/{id}', [BillController::class, 'billWithBillDetail']);
Route::post('bills-confirm/{id}', [BillController::class, 'confirm']);
Route::post('bills-paid/{id}', [BillController::class, 'paid']);
Route::post('bills-shiping/{id}', [BillController::class, 'shiping']);
Route::post('bills-paidShiping/{id}', [BillController::class, 'paidShiping']);
Route::post('bills-cancel/{id}', [BillController::class, 'cancel']);

//BillDetail
Route::apiResource('bill-details', BillDetailController::class);


//Order
Route::apiResource('orders', OrderController::class);
Route::get('order-with-orderDetail/{id}', [OrderController::class, 'orderWithOrderDetail']);

//OrderDetail
Route::apiResource('order-details', OrderDetailController::class);
Route::post('orderDetailWithVariant', [OrderDetailController::class, 'orderDetailWithVariant']);

//Voucher
// Route::apiResource('voucher', VoucherController::class);

//Sale
Route::apiResource('sales', SaleController::class);
Route::get('sale-onlayout', [SaleController::class, 'getOnlayout']);
Route::get('sale-product/{id}', [SaleController::class, 'saleWithProduct']);

Route::post('register', [\App\Http\Controllers\API\AuthController::class, 'register'])->name('register');
Route::post('login', [\App\Http\Controllers\API\AuthController::class, 'login'])->name('login');


Route::group(['middleware' => 'auth:sanctum'], function () {

    // Route::get('/profile', function (Request $request) {
    //     return auth()->users();
    // });

    Route::get('logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])->name('logout');
});
