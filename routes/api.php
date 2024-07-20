<?php

use App\Http\Controllers\API\AttributeController;
use App\Http\Controllers\API\AttributeValueController;
use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\BillDetailController;
use App\Http\Controllers\API\BillStoryController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderDetailController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\StatisticalController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VariantController;
use App\Http\Controllers\API\VnpayController;
use App\Http\Controllers\API\VoucherController;
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
// ======================= ADMIN ========================
Route::middleware(['auth:sanctum', 'checkAdmin'])->group(function () {
    Route::prefix('admin/')->group(function () {
        // //Categories
        // Route::apiResource("categories", \App\Http\Controllers\API\CategoryController::class);
        // Route::get('categories/{id}/total-products', [\App\Http\Controllers\API\CategoryController::class, 'totalProducts']);

        // Product
        //Route::apiResource("products", ProductController::class);
        // Route::apiResource("products", \App\Http\Controllers\API\ProductController::class);
        // Route::apiResource("variants", \App\Http\Controllers\API\VariantController::class);
        // Route::apiResource("attributes", \App\Http\Controllers\API\AttributeController::class);

        Route::apiResource('bill-details', BillDetailController::class);

        // //User
        // Route::apiResource('users', UserController::class);

        // post
            Route::get('posts/{id}', [PostController::class, 'show']);
            Route::put('posts/{id}', [PostController::class, 'update']);
            Route::delete('posts/{id}', [PostController::class, 'destroy']);
            Route::post('posts', [PostController::class, 'store']);
    });
});
// ======================= USER ========================
//Categories
Route::apiResource("categories", CategoryController::class);
Route::get("get-category-by-name", [CategoryController::class, 'getCategoryByName']);

//Product
Route::get('user/product', [ProductController::class, 'index']);
Route::apiResource("products", ProductController::class);
Route::put("update-sale-in-product/{id}", [ProductController::class, 'updateSaleInProduct']);
Route::get("get-by-sale", [ProductController::class, 'getBySale']);
Route::get("get-by-sale/{id}", [ProductController::class, 'getProductBySaleId']);
Route::get("get-by-sale-id", [ProductController::class, 'getBySaleId']);
Route::get("get-by-name", [ProductController::class, 'getProductByName']);
Route::get("get-by-category", [ProductController::class, 'getProductByCategory']);
Route::get("filter", [ProductController::class, 'filter']);

//Variants
Route::apiResource("variants", VariantController::class);

//Attribute_name
Route::apiResource("attributes", AttributeController::class);


//Attribute_value
Route::apiResource("attribute-values", AttributeValueController::class);


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
Route::get('bills-cancel', [BillController::class, 'getCancel']);
Route::get('bills-with-user/{id}', [BillController::class, 'getBillWithUser']);
Route::get('bills-with-user-pending/{id}', [BillController::class, 'getBillWithUserPending']);
Route::get('bills-with-user-done/{id}', [BillController::class, 'getBillWithUserDone']);
Route::get('bills-with-user-paid/{id}', [BillController::class, 'getBillWithUserPaid']);
Route::get('bills-with-user-shiping/{id}', [BillController::class, 'getBillWithUserShiping']);
Route::get('bills-with-user-cancel/{id}', [BillController::class, 'getBillWithUserCancel']);
Route::get('bills-with-user-confirm/{id}', [BillController::class, 'getBillWithUserConfirm']);
Route::get('bills-with-phone/{phone}', [BillController::class, 'getBillWithphone']);
Route::get('bills-with-email/{email}', [BillController::class, 'getBillWithEmail']);

//Statistical
Route::get('revenue-by-day', [StatisticalController::class, 'getTotalByDate']);//doanh thu ngay
Route::get('status-by-day', [StatisticalController::class, 'getStatusByDate']);//trang thai ngay
Route::get('count-pay', [StatisticalController::class, 'getBillPay']);//dem phuong thuc mua hang bill.pay
Route::get('revenue-week-by-day', [StatisticalController::class, 'getRevenuesWeekDay']);//doanh thu cac don hang trong tuan nay
Route::get('revenue-sevent-last-day', [StatisticalController::class, 'revenuesLast7Days']);// doanh thu trong 7 ngay qua
Route::get('revenue-by-week', [StatisticalController::class, 'getTotalByWeek']);
Route::get('revenue-by-month-with-week', [StatisticalController::class, 'getTotalByMonthWithWeek']);//thong ke doanh thu thang nay theo cac tuan
Route::post('revenue-by-month', [StatisticalController::class, 'revenueMonthly']);//thong ke doanh thu cua 1 thang cu the function(month,year)
Route::post('revenue-by-between-date', [StatisticalController::class, 'revenuesBetweenDates']);//thong ke doanh thu theo ngay tu begin den end function(start_date,end_date)
Route::post('revenue-by-date', [StatisticalController::class, 'revenueForSpecificDate']);//thong ke doanh thu theo ngay cu the function(date)
Route::get('revenue-by-year-now', [StatisticalController::class, 'revenueAnnualRevenue']);//thong ke doanh thu cua nam hien tai
Route::get('revenue-by-year-any/{year}', [StatisticalController::class, 'getAnnualRevenueAnyYear']);//thong ke daonh thu cau 1 nam bat ki
Route::get('top-user/{top}', [StatisticalController::class, 'getTopUser']);//thong ke top user theo tham so top function(top)
Route::get('top-product/{top}', [StatisticalController::class, 'getBestSellingProducts']);//thong ke top product theo tham so function(top)
Route::get('revenue-by-month', [StatisticalController::class, 'getTotalByMonth']);
Route::get('revenue-by-product', [StatisticalController::class, 'getTotalByProduct']);
Route::get('quantity-by-day', [StatisticalController::class, 'getTotalQuantitySoldDaily']);
Route::get('quantity-by-week', [StatisticalController::class, 'getTotalQuantitySoldWeek']);
Route::get('quantity-by-month', [StatisticalController::class, 'getTotalQuantitySoldMonth']);
Route::get('product-best-seller', [StatisticalController::class, 'getProductBestSeller']);
Route::get('new-user-by-day', [StatisticalController::class, 'newRegistrationsToday']);
Route::get('new-user-by-week', [StatisticalController::class, 'newRegistrationsThisWeek']);
Route::get('new-user-by-month', [StatisticalController::class, 'newRegistrationsThisMonth']);
Route::get('order-statistical', [StatisticalController::class, 'getOrderStatistics']);
Route::get('revenue-category', [StatisticalController::class, 'revenueCategory']);

//BillDetail


// BillStory
Route::apiResource('bill-stores', BillStoryController::class);
Route::get('bill-stores-with-bill/{bill_id}', [BillController::class, 'billStoryWithBill']);

//Cart
Route::post('cart', [OrderDetailController::class, 'orderDetailWithVariant']);

//Voucher
Route::apiResource('voucher', VoucherController::class);

//Sale
Route::apiResource('sales', SaleController::class);
Route::get('sale-onlayout', [SaleController::class, 'getOnlayout']);
Route::get('sale-product/{id}', [SaleController::class, 'saleWithProduct']);

// vnpay
Route::get('pay/{bill_id}/{amount}/{bank_code}', [VnpayController::class, 'checkout'])->name('checkout_vnpay');
//login
Route::post('register', [\App\Http\Controllers\API\AuthController::class, 'register'])->name('register');
Route::post('login', [\App\Http\Controllers\API\AuthController::class, 'login'])->name('login');


Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::get('/profile', function (Request $request) {
    //     return auth()->users();
    // });
    Route::get('posts/{id}', [PostController::class, 'show']);
    Route::put('posts/{id}', [PostController::class, 'update']);
    Route::delete('posts/{id}', [PostController::class, 'destroy']);
    Route::post('posts', [PostController::class, 'store']);

    Route::get('logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])->name('logout');
});
// vnpay
Route::get('pay/{bill_id}/{amount}/{bank_code}', [VnpayController::class, 'checkout'])->name('checkout_vnpay');
//post
Route::get('posts', [PostController::class, 'index']);
