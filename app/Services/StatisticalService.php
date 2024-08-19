<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticalService
{
    public function getTotalRevenueByDay()
    {
        $today = date('Y-m-d');
        try {
            $revenues = Bill::join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
                ->select(
                    DB::raw('DATE(bills.created_at) as date'),
                    DB::raw('SUM(bills.total_amount) as total_revenue'),
                    DB::raw('SUM(bill_details.quantity) as total_quantity_sold')
                )
                ->whereDate('bills.created_at', $today)
                ->whereIn('bills.status', ['Done', 'Paid'])
                ->groupBy(DB::raw('DATE(bills.created_at)'))
                ->orderBy('date', 'asc')
                ->first();

            return $revenues;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getTotalRevenuePaidByDay()
    {
        $today = date('Y-m-d');
        try {
            $revenues = Bill::join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
                ->select(
                    DB::raw('DATE(bills.created_at) as date'),
                    DB::raw('SUM(bills.total_amount) as total_revenue'),
                    DB::raw('SUM(bill_details.quantity) as total_quantity_sold')
                )
                ->whereDate('bills.created_at', $today)
                ->whereIn('bills.status', ['Paid'])
                ->groupBy(DB::raw('DATE(bills.created_at)'))
                ->orderBy('date', 'asc')
                ->first();

            return $revenues;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function getBillCountsByStatus(){
        $today = date('Y-m-d');
        try {
            $orderCounts = Bill::select(
                    'status',
                    DB::raw('COUNT(*) as total_bill')
                )
                ->whereDate('created_at', $today)
                ->groupBy('status')
                ->get();

            return $orderCounts;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getBillCountPay(){
        try {
            $orderCounts = Bill::select(
                    'pay',
                    DB::raw('COUNT(*) as total_pay')
                )
                ->groupBy('pay')
                ->get();

            return $orderCounts;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getRevenuesWeekByDay(){
        try {
            $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
            $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

            $revenues = DB::table('bills')
                ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
                ->select(
                    DB::raw('DATE(bills.created_at) as date'),
                    DB::raw('SUM(bills.total_amount) as total_revenue'),
                    DB::raw('SUM(bill_details.quantity) as total_quantity_sold')
                )
                ->whereBetween(DB::raw('DATE(bills.created_at)'), [$startOfWeek, $endOfWeek])
                ->whereIn('bills.status', ['Done', 'Paid'])
                ->groupBy(DB::raw('DATE(bills.created_at)'))
                ->orderBy('date', 'asc')
                ->get();

            $totalRevenue = $revenues->sum('total_revenue');
            $totalQuantitySold = $revenues->sum('total_quantity_sold');


            $result = [
                'daily_revenues' => $revenues,
                'total_revenue' => $totalRevenue,
                'total_quantity_sold' => $totalQuantitySold,
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'khong the thong ke doanh thu'], 500);
        }
    }


    public function getRevenuesLast7Days()
    {
        try {
            $now = Carbon::now()->addDay()->format('Y-m-d');
            $sevenDaysAgo = Carbon::now()->subDays(7)->format('Y-m-d');
            Log::info($sevenDaysAgo.'-'.$now);
            $query = DB::table('bills')
                ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
                ->select(
                    DB::raw('DATE(bills.created_at) as date'),
                    DB::raw('SUM(bills.total_amount) as total_revenue'),
                    DB::raw('SUM(bill_details.quantity) as total_quantity_sold')
                )
                ->whereBetween('bills.created_at', [$sevenDaysAgo, $now])
                ->groupBy(DB::raw('DATE(bills.created_at)'))
                ->orderBy(DB::raw('DATE(bills.created_at)'), 'asc');
            $revenues = $query->get();
            $totalRevenue = $revenues->sum('total_revenue');
            $totalQuantitySold = $revenues->sum('total_quantity_sold');
            $result = [
                'daily_revenues' => $revenues,
                'total_revenue' => $totalRevenue,
                'total_quantity_sold' => $totalQuantitySold,
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'khong the thong ke doanh thu'], 500);
        }
    }

    public function getTotalRevenueByWeek()
    {
        try {
            $revenues = Bill::select(
                DB::raw('YEARWEEK(created_at) as week'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
                ->where('status', 'Done', 'Paid')
                ->groupBy('week')
                ->orderBy('week', 'asc')
                ->get();
            return $revenues;
        } catch (\Exception $e) {
            Log::error('loi khi tinh tong doanh thu theo tuần: ') . $e->getMessage();
            throw $e;
        }
    }

    public function getRevenuesCurrentMonthByWeek() {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $weeklyRevenues = [];
            $startOfWeek = $startOfMonth->copy();

            while ($startOfWeek->lte($endOfMonth)) {
                $endOfWeek = $startOfWeek->copy()->endOfWeek();

                if ($endOfWeek->gt($endOfMonth)) {
                    $endOfWeek = $endOfMonth;
                }

                $revenues = DB::table('bills')
                    ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
                    ->select(
                        DB::raw('SUM(bills.total_amount) as total_revenue'),
                        DB::raw('SUM(bill_details.quantity) as total_quantity_sold')
                    )
                    ->whereBetween('bills.created_at', [$startOfWeek, $endOfWeek])
                    ->whereIn('bills.status', ['Done', 'Paid'])
                    ->first();

                $weeklyRevenues[] = [
                    'start_of_week' => $startOfWeek->toDateString(),
                    'end_of_week' => $endOfWeek->toDateString(),
                    'total_revenue' => $revenues->total_revenue ?? 0,
                    'total_quantity_sold' => $revenues->total_quantity_sold ?? 0,
                ];

                $startOfWeek->addWeek();
            }

            $totalRevenue = array_sum(array_column($weeklyRevenues, 'total_revenue'));
            $totalQuantitySold = array_sum(array_column($weeklyRevenues, 'total_quantity_sold'));
            $result = [
                'weekly_revenues' => $weeklyRevenues,
                'total_revenue' => $totalRevenue,
                'total_quantity_sold' => $totalQuantitySold,
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Kh�ng th? th?ng k� doanh thu'], 500);
        }
    }


    public function getRevenuesBetweenDates($request) {
        try {
            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

            $revenues = DB::table('bills')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_amount) as total_revenue')
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', ['Done', 'Paid'])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date', 'asc')
                ->get();

            $totalRevenue = $revenues->sum('total_revenue');

            $result = [
                'daily_revenues' => $revenues,
                'total_revenue' => $totalRevenue,
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'khong thong ke duoc doanh thu'], 500);
        }
    }

    public function getRevenueForSpecificDate($request) {
        try {
            $specificDate = Carbon::parse($request->input('date'))->startOfDay();
            $endOfSpecificDate = $specificDate->copy()->endOfDay();
            $revenue = DB::table('bills')
                ->select(
                    DB::raw('SUM(total_amount) as total_revenue')
                )
                ->whereBetween('created_at', [$specificDate, $endOfSpecificDate])
                ->whereIn('status', ['Done', 'Paid'])
                ->first();

            $result = [
                'date' => $specificDate->toDateString(),
                'total_revenue' => $revenue->total_revenue ?? 0,
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'khong thong ke duoc doanh thu'], 500);
        }
    }

    public function getTotalRevenueByMonth()
    {
        try {
            $revenues = Bill::select(
                DB::raw('DATE_FORMAT(created_at, "%y-%m") as month'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
                ->where('status', 'Done', 'Paid')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
            return $revenues;
        } catch (\Exception $e) {
            Log::error('loi khi tinh tong doanh thu theo thang: ') . $e->getMessage();
            throw $e;
        }
    }

    public function getTotalRevenueByProduct()
    {
        try {
            $revenues = BillDetail::join('bills', 'bill_details.bill_id', '=', 'bills.id')
                ->select(
                    'bill_details.product_name',
                    DB::raw('SUM(bill_details.price * bill_details.quantity) as total_revenue')
                )
                ->whereIn('bills.status', ['Done', 'Paid'])
                ->groupBy('bill_details.product_name')
                ->orderBy('total_revenue', 'desc')
                ->get();
            return response()->json($revenues);
        } catch (\Exception $e) {
            Log::error('Loi khi tinh tong doanh thu theo san pham: ' . $e->getMessage());
            return response()->json(['error' => 'Loi tinh tong doanh thu'], 500);
        }
    }

    public function getTotalQuantitySoldDaily()
    {
        try {
            $quantities = Bill::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as quantity')
            )
                ->where('status', 'Done', 'Paid')
                ->groupBy('date')
                ->orderBy('quantity', 'asc')
                ->get();
            return response()->json($quantities);
        } catch (\Exception $e) {
            Log::error('loi khi tinh tong so luong ban ra hang ngay: ' . $e->getMessage());
            return response()->json(['error' => 'loi tinh tong so luong'], 500);
        }
    }
    public function getTotalQuantitySoldWeek()
    {
        try {
            $quantities = Bill::select(
                DB::raw('YEARWEEK(created_at) as week'),
                DB::raw('count(*) as quantity')
            )
                ->where('status', 'Done', 'Paid')
                ->groupBy('week')
                ->orderBy('quantity', 'ASC')
                ->get();
            return response()->json($quantities);
        } catch (\Exception $e) {
            Log::error('loi khi tinh tong so luong ban ra hang ngay: ' . $e->getMessage());
            return response()->json(['error' => 'loi tinh tong so luong'], 500);
        }
    }

    public function getTotalQuantitySoldMonth()
    {
        try {
            $quantities = BillDetail::select(
                DB::raw('DATE_FORMAT(created_at, "%y-%m") as month'),
                DB::raw('count(*) as quantity')
            )
                ->where('status', 'Done', 'Paid')
                ->groupBy('month')
                ->orderBy('quantity', 'ASC')
                ->get();
            return response()->json($quantities);
        } catch (\Exception $e) {
            Log::error('loi khi tinh tong so luong ban ra hang ngay: ' . $e->getMessage());
            return response()->json(['error' => 'loi tinh tong so luong'], 500);
        }
    }
    public function getProductBestSeller()
    {
        try {
            $bestSeller = BillDetail::select(
                'product_name',
                'quantity'
            )
                ->orderBy('quantity', 'DESC')
                ->get();
            return response()->json($bestSeller);
        } catch (\Exception $e) {
            Log::error('loi khi lay san pham ban chay nhat' . $e->getMessage());
            throw $e;
        }
    }

    public function newRegistrationsToday()
    {
        try {
            $today = Carbon::today();
            $newRegistrations  = User::whereDate('created_at', $today)->count();
            return response()->json($newRegistrations);
        } catch (\Exception $e) {
            Log::error('loi khi lay user moi' . $e->getMessage());
            throw $e;
        }
    }

    public function newRegistrationsThisWeek()
    {
        try {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $newRegistrations = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            return response()->json($newRegistrations);
        } catch (\Exception $e) {
            Log::error('loi khi lay user moi' . $e->getMessage());
            throw $e;
        }
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $newRegistrations = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
    }

    public function newRegistrationsThisMonth()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $newRegistrations = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            return response()->json($newRegistrations);
        } catch (\Exception $e) {
            Log::error('loi khi lay user moi' . $e->getMessage());
            throw $e;
        }
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $newRegistrations = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
    }

    public function getOrderStatistics()
    {
        try {
            $totalOrders = Bill::count();
            $averageOrderValue = Bill::avg('total_amount');

            return response()->json([
                'total_orders' => $totalOrders,
                'average_order_value' => $averageOrderValue
            ]);
        } catch (\Exception $e) {
            Log::error('loi khi lay order moi' . $e->getMessage());
            throw $e;
        }
    }

    public function getAnnualRevenue() {
        try {
            $startOfYear = Carbon::now()->startOfYear();
            $endOfYear = Carbon::now()->endOfYear();
            $revenues = DB::table('bills')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total_amount) as total_revenue')
                )
                ->whereBetween('created_at', [$startOfYear, $endOfYear])
                ->whereIn('status', ['Done', 'Paid'])
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy('month', 'asc')
                ->get();
            $monthlyRevenues = [];
            foreach ($revenues as $revenue) {
                $monthlyRevenues[] = [
                    'month' => $revenue->month,
                    'total_revenue' => $revenue->total_revenue,
                ];
            }
            $totalRevenue = array_sum(array_column($monthlyRevenues, 'total_revenue'));

            $result = [
                'monthly_revenues' => $monthlyRevenues,
                'total_revenue' => $totalRevenue,
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'khong thong ke duoc doanh thu'], 500);
        }
    }

    public function getMonthlyRevenue($request) {
        try {
            $month = $request->input('month');
            $year = $request->input('year');
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            $revenues = DB::table('bills')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_amount) as total_revenue')
                )
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->whereIn('status', ['Done', 'Paid'])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date', 'asc')
                ->get();
            $totalRevenue = $revenues->sum('total_revenue');
            $result = [
                'daily_revenues' => $revenues,
                'total_revenue' => $totalRevenue,
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('loi thong ke thang: ' . $e->getMessage());
            return response()->json(['error' => 'khongt thong ke duoc daonh thu'], 500);
        }
    }

    public function topUsers($request)
    {
        $top = $request;
        return DB::table('bill_details')
            ->join('bills', 'bill_details.bill_id', '=', 'bills.id')
            ->join('users', 'bills.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('SUM(bill_details.quantity) as total_products_bought'),
                DB::raw('COUNT(bills.id) as total_orders')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_products_bought', 'DESC')
            ->take($top)
            ->get();
    }

    public function bestSellingProducts($request)
    {
        $top = $request;
        return DB::table('bill_details')
            ->join('bills', 'bill_details.bill_id', '=', 'bills.id')
            ->select(
                'bill_details.product_name',
                DB::raw('SUM(bill_details.quantity) as total_quantity_sold'),
                DB::raw('COUNT(bill_details.id) as sold_count')
            )
            ->whereIn('bills.status', ['Done', 'Paid'])
            ->groupBy('bill_details.product_name')
            ->orderBy('total_quantity_sold', 'DESC')
            ->take($top)
            ->get();
    }

    public function  annualRevenueAnyYear($year){
        return DB::table('bills')
        ->select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_amount) as total_revenue')
        )
        ->whereYear('created_at', '=', $year)
        ->whereIn('status', ['Done', 'Paid'])
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy(DB::raw('MONTH(created_at)'))
        ->get();
    }

    public function getProductCounts()
    {
        $categories = Category::withCount('products')->get();
        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    function getTodayStatistics() {
        $today = Carbon::today();
        $totalSalesToday = DB::table('bills')
            ->whereDate('created_at', $today)
            ->whereIn('status', ['Done', 'Paid'])
            ->sum('total_amount');

        $orderCountToday = DB::table('bills')
            ->whereDate('created_at', $today)
            ->count();

        $newCustomersToday = DB::table('users')
            ->whereDate('created_at', $today)
            ->count();

        $totalCustomersUntilToday = DB::table('users')
            ->whereDate('created_at', '<=', $today)
            ->count();

        $onlinePaidOrdersToday = DB::table('bills')
            ->whereDate('created_at', $today)
            ->where('status', 'Paid')
            ->count();

        return [
            'total_sales_today' => $totalSalesToday,
            'order_count_today' => $orderCountToday,
            'new_customers_today' => $newCustomersToday,
            'total_customers_until_today' => $totalCustomersUntilToday,
            'online_paid_orders_today' => $onlinePaidOrdersToday,
        ];
    }

    function getWeekStatistics() {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $totalSalesWeek = DB::table('bills')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['Done', 'Paid'])
            ->sum('total_amount');

        $orderCountWeek = DB::table('bills')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();

        $newCustomersWeek = DB::table('users')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();

        $totalCustomersUntilEndOfWeek = DB::table('users')
            ->whereDate('created_at', '<=', $endOfWeek)
            ->count();

        $onlinePaidOrdersWeek = DB::table('bills')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'Paid')
            ->count();

        return [
            'total_sales_week' => $totalSalesWeek,
            'order_count_week' => $orderCountWeek,
            'new_customers_week' => $newCustomersWeek,
            'total_customers_until_end_of_week' => $totalCustomersUntilEndOfWeek,
            'online_paid_orders_week' => $onlinePaidOrdersWeek,
        ];
    }

    function getMonthStatistics() {
        $startOfWeek = Carbon::now()->startOfMonth();
        $endOfWeek = Carbon::now()->endOfMonth();

        $totalSalesWeek = DB::table('bills')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['Done', 'Paid'])
            ->sum('total_amount');

        $orderCountWeek = DB::table('bills')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();

        $newCustomersWeek = DB::table('users')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();

        $totalCustomersUntilEndOfWeek = DB::table('users')
            ->whereDate('created_at', '<=', $endOfWeek)
            ->count();

        $onlinePaidOrdersWeek = DB::table('bills')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'Paid')
            ->count();

        return [
            'total_sales_week' => $totalSalesWeek,
            'order_count_week' => $orderCountWeek,
            'new_customers_week' => $newCustomersWeek,
            'total_customers_until_end_of_week' => $totalCustomersUntilEndOfWeek,
            'online_paid_orders_week' => $onlinePaidOrdersWeek,
        ];
    }
}
