<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticalService
{
    public function getTotalRevenueByDay()
    {
        try {
            $revenues = Bill::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
                ->where('status', 'Done', 'Paid')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();
            return $revenues;
        } catch (\Exception $e) {
            Log::error('loi khi tinh tong doanh thu theo ngay: ') . $e->getMessage();
            throw $e;
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
}
