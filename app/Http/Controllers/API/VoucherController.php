<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\VouchersRequest;
use App\Services\VoucherService;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VoucherController extends Controller
{
    use APIResponse;
    protected $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    public function index()
    {
        $voucher = $this->voucherService->getAllVoucher();
        return $this->responseSuccess(
            __('lay danh sach thanh cong'),
            ['data' => $voucher]
        );
    }

    public function store(VouchersRequest $request)
    {
        $data = $request->all();
        $voucher = $this->voucherService->storeVoucher($data);
        return $this->responseSuccess(
            __('them thanh cong'),
            ['data' => $voucher]
        );
    }

    public function show(int $id)
    {
        $voucher = $this->voucherService->showVoucher($id);
        if (!$voucher) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('Khong timf thay voucher')
            );
        } else {
            return $this->responseSuccess(
                __('hien thi voucher thanh cong'),
                ['data' => $voucher],
            );
        }
    }

    public function update(VouchersRequest $request, int $id)
    {
        $data = $request->all();
        $voucher = $this->voucherService->updateVoucher($id, $data);
        if (!$voucher) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay voucher')
            );
        } else {
            return $this->responseSuccess(
                __('sua thanh cong'),
                ['data' => $voucher]
            );
        };
    }

    public function destroy(int $id)
    {
        $voucher = $this->voucherService->destroyVoucher($id);
        return $this->responseSuccess(
            __('xoa thanh cong'),
            ['data' => $voucher]
        );
    }
}
