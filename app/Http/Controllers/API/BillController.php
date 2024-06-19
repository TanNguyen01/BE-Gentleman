<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\BillRequest;
use App\Traits\APIResponse;
use Illuminate\Http\Response;
use App\Services\BillService;
use Illuminate\Http\JsonResponse;

class BillController extends BillService
{
    use APIResponse;
    protected $billService;
    function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->billService->getAllBill();
        return $this->responseCreated(
            __('tao danh muc thanh cong'),
            [
                'data' => $data,
            ]
        );
    }

    public function store(BillRequest $request)
    {
        $request = $request->validated();
        $data = $this->billService->storeBill($request);
        return $this->responseCreated(
            __('tao danh muc thanh cong'),
            [
                'data' => $data,
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->billService->showBill($id);
        if (!$data) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc')
            );
        } else {
            return $this->responseSuccess(
                __('hien thi danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function billWithBillDetail($id){
        $data = $this->billService->eloquentBillWithBillDetail($id);
        if (!$data) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong thay bill detail')
            );
        } else {
            return $this->responseSuccess(
                __('hien thi danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function confirm(string $id)
    {
        $data = $this->billService->chanceStatusConfirm($id);
        if(!$data){
            return $this->responseNotFound(Response::HTTP_NOT_FOUND,
            __('khong tim thay danh muc'),
            );
        }else{
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => 'confirm',
                ]
            );
        }
    }

    public function shiping(string $id)
    {
        $data = $this->billService->chanceStatusConfirm($id);
        if(!$data){
            return $this->responseNotFound(Response::HTTP_NOT_FOUND,
            __('khong tim thay danh muc'),
            );
        }else{
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => 'shiping',
                ]
            );
        }
    }

    public function paid(string $id)
    {
        $data = $this->billService->chanceStatusPaid($id);
        if(!$data){
            return $this->responseNotFound(Response::HTTP_NOT_FOUND,
            __('khong tim thay danh muc'),
            );
        }else{
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => 'paid',
                ]
            );
        }
    }

    public function paidShiping(string $id)
    {
        $data = $this->billService->chanceStatusPaidShiping($id);
        if(!$data){
            return $this->responseNotFound(Response::HTTP_NOT_FOUND,
            __('khong tim thay danh muc'),
            );
        }else{
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => 'paidShiping',
                ]
            );
        }
    }

    public function cancel(string $id)
    {
        $data = $this->billService->chanceStatusCancel($id);
        if(!$data){
            return $this->responseNotFound(Response::HTTP_NOT_FOUND,
            __('khong tim thay danh muc'),
            );
        }else{
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => 'paidShiping',
                ]
            );
        }
    }
}
