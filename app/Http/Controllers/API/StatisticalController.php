<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\StatisticalService;
use App\Traits\APIResponse;
use Illuminate\Http\Response;

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
                __('khong co doanh thu hom nay'),
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
}
