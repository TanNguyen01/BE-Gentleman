<?php

use App\Http\Controllers\API\AttributeController;
use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\BillDetailController;
use App\Http\Controllers\API\BillStoryController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ColorController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\OrderDetailController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\SizeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VariantController;
use App\Http\Controllers\SizeController as ControllersSizeController;
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


Route::get("filter",[ProductController::class, 'filterByPrice']);
Route::get("filterColor",[ProductController::class, 'filterByColor']);
Route::get("filterSize",[ProductController::class, 'filterBySize']);
Route::get("filterCategory",[ProductController::class, 'filterByCategory']);






//size
Route::get("get-all-size", [SizeController::class, 'index']);
Route::post("add-size", [SizeController::class, 'store']);


//color
Route::get("get-all-color", [ColorController::class, 'index']);
Route::post("add-color", [ColorController::class, 'store']);


//User

Route::apiResource('users', UserController::class);


//Bill
Route::apiResource('bills', BillController::class);
Route::get('bills-with-billDetail/{id}', [BillController::class, 'billWithBillDetail']);
Route::post('bills-confirm/{id}', [BillController::class, 'confirm']);
Route::post('bills-pending/{id}', [BillController::class, 'pending']);
Route::post('bills-done/{id}', [BillController::class, 'done']);
Route::post('bills-paid/{id}', [BillController::class, 'paid']);
Route::post('bills-shiping/{id}', [BillController::class, 'shiping']);
Route::post('bills-paidShiping/{id}', [BillController::class, 'paidShiping']);
Route::post('bills-cancel/{id}', [BillController::class, 'cancel']);
Route::get('bills-confirm', [BillController::class, 'getConfirm']);
Route::get('bills-pending', [BillController::class, 'getPending']);
Route::get('bills-done', [BillController::class, 'getDone']);
Route::get('bills-paid', [BillController::class, 'getPaid']);
Route::get('bills-shiping', [BillController::class, 'getShiping']);
Route::get('bills-paidShiping', [BillController::class, 'getPaidShiping']);
Route::get('bills-cancel', [BillController::class, 'getCancel']);
Route::get('bills-with-user/{id}', [BillController::class, 'getBillWithUser']);

//BillDetail
Route::apiResource('bill-details', BillDetailController::class);

// BillStory
Route::apiResource('bill-stores', BillStoryController::class);

//Order
Route::apiResource('orders', OrderController::class);
Route::get('order-with-orderDetail/{id}', [OrderController::class, 'orderWithOrderDetail']);

//OrderDetail
Route::apiResource('order-details', OrderDetailController::class);
Route::post('cart', [OrderDetailController::class, 'orderDetailWithVariant']);

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
