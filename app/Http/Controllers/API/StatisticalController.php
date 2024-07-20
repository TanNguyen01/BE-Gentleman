<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\StatisticalService;
use App\Traits\APIResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    use APIResponse;
    protected $statisticalService;

    public function __construct(StatisticalService $statisticalService)
    {
        $this->statisticalService = $statisticalService;
    }

    public function getTotalByDate()
    {
        $revenues = $this->statisticalService->getTotalRevenueByDay();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('chua co doanh thu hom nay'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu hom nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function getTotalPaidByDate()
    {
        $revenues = $this->statisticalService->getTotalRevenuePaidByDay();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('chua co doanh thu hom nay'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu hom nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function getStatusByDate()
    {
        $revenues = $this->statisticalService->getBillCountsByStatus();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('xay ra loi'),
            );
        } else {
            return $this->responseSuccess(
                __('trang thai don hang hom nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function getBillPay()
    {
        $revenues = $this->statisticalService->getBillCountPay();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('xay ra loi'),
            );
        } else {
            return $this->responseSuccess(
                __('trang thai don hang hom nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function getRevenuesWeekDay(){
        $revenues = $this->statisticalService->getRevenuesWeekByDay();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('xay ra loi'),
            );
        } else {
            return $this->responseSuccess(
                __('trang thai don hang hom nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function revenuesLast7Days(){
        $revenues = $this->statisticalService->getRevenuesLast7Days();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('xay ra loi'),
            );
        } else {
            return $this->responseSuccess(
                __('trang thai don hang 7 ngay qua'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function getTotalByWeek()
    {
        $revenues = $this->statisticalService->getTotalRevenueByWeek();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co doanh thu tuan nay'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu tuan nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function getTotalByMonthWithWeek()
    {
        $revenues = $this->statisticalService->getRevenuesCurrentMonthByWeek();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co doanh thu thang nay'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu thang nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function revenueMonthly(Request $request)
    {
        $revenues = $this->statisticalService->getMonthlyRevenue($request);
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co doanh thu thang nay'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu thang nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function revenuesBetweenDates(Request $request)
    {
        $revenues = $this->statisticalService->getRevenuesBetweenDates($request);
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co doanh thu thang nay'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu thang nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function revenueForSpecificDate(Request $request)
    {
        $revenues = $this->statisticalService->getRevenueForSpecificDate($request);
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co doanh thu thang nay'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu thang nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function getTotalByMonth()
    {
        $revenues = $this->statisticalService->getTotalRevenueByMonth();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co doanh thu thang nay'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu thang nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function revenueAnnualRevenue()
    {
        $revenues = $this->statisticalService->getAnnualRevenue();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co doanh thu thang nay'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu thang nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function getTotalByProduct()
    {
        $revenues = $this->statisticalService->getTotalRevenueByProduct();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co doanh thu san pham nay'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu san pham nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }
    public function getTotalQuantitySoldDaily()
    {
        $quantities = $this->statisticalService->getTotalQuantitySoldDaily();
        if (!$quantities) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co so luong san pham trong ngay'),
            );
        } else {
            return $this->responseSuccess(
                __('so luong ban san pham trong ngay'),
                [
                    'data' => $quantities,
                ]
            );
        }
    }
    public function getTotalQuantitySoldWeek()
    {
        $quantities = $this->statisticalService->getTotalQuantitySoldWeek();
        if (!$quantities) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co so luong san pham trong tuan'),
            );
        } else {
            return $this->responseSuccess(
                __('so luong ban san pham trong tuan'),
                [
                    'data' => $quantities,
                ]
            );
        }
    }
    public function getTotalQuantitySoldMonth()
    {
        $quantities = $this->statisticalService->getTotalQuantitySoldMonth();
        if (!$quantities) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co so luong san pham trong thang'),
            );
        } else {
            return $this->responseSuccess(
                __('so luong ban san pham trong thang'),
                [
                    'data' => $quantities,
                ]
            );
        }
    }

    public function getProductBestSeller()
    {
        $bestSeller = $this->statisticalService->getProductBestSeller();
        if (!$bestSeller) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co so luong san pham trong thang'),
            );
        } else {
            return $this->responseSuccess(
                __('so luong ban san pham trong thang'),
                [
                    'data' => $bestSeller,
                ]
            );
        }
    }

    public function newRegistrationsToday()
    {
        $newRegistrations = $this->statisticalService->newRegistrationsToday();
        if (!$newRegistrations) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co user moi trong ngay'),
            );
        } else {
            return $this->responseSuccess(
                __('so luong user moi trong ngay'),
                [
                    'data' => $newRegistrations,
                ]
            );
        }
    }

    public function newRegistrationsThisWeek()
    {
        $newRegistrations = $this->statisticalService->newRegistrationsThisWeek();
        if (!$newRegistrations) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co user moi trong tuan'),
            );
        } else {
            return $this->responseSuccess(
                __('so luong user moi trong tuan'),
                [
                    'data' => $newRegistrations,
                ]
            );
        }
    }

    public function newRegistrationsThisMonth()
    {
        $newRegistrations = $this->statisticalService->newRegistrationsThisMonth();
        if (!$newRegistrations) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong co user moi trong thang'),
            );
        } else {
            return $this->responseSuccess(
                __('so luong user moi trong thang'),
                [
                    'data' => $newRegistrations,
                ]
            );
        }
    }

    public function getOrderStatistics()
    {
        $totalOrder = $this->statisticalService->getOrderStatistics();
        if (!$totalOrder) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('loi order'),
            );
        } else {
            return $this->responseSuccess(
                __('tong so don hang va gia tri trung binh moi don'),
                [
                    'data' => $totalOrder,
                ]
            );
        }
    }

    public function getTopUser($top)
    {
        $revenues = $this->statisticalService->topUsers($top);
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong thay top user'),
            );
        } else {
            return $this->responseSuccess(
                __('top'.$top.'user'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function getBestSellingProducts($top)
    {
        $revenues = $this->statisticalService->bestSellingProducts($top);
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong thay top product'),
            );
        } else {
            return $this->responseSuccess(
                __('top '.$top.' product'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function getAnnualRevenueAnyYear($year)
    {
        $revenues = $this->statisticalService->annualRevenueAnyYear($year);
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('khong thay du lieu cua nam'.$year),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu cua nam'.$year),
                [
                    'data' => $revenues,
                ]
            );
        }
    }

    public function revenueCategory()
    {
        $revenues = $this->statisticalService->getProductCounts();
        if (!$revenues) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('loi category'),
            );
        } else {
            return $this->responseSuccess(
                __('doanh thu thang nay'),
                [
                    'data' => $revenues,
                ]
            );
        }
    }
}
