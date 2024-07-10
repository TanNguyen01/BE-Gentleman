<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\BillRequest;
use App\Traits\APIResponse;
use Illuminate\Http\Response;
use App\Services\BillService;

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
        $request = $request->all();
        $data = $this->billService->storeBill($request);
        if($data == true){
            return $this->responseCreated(
                __('tao danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
        else{
            return $this->responseCreated(
                __('tao danh muc khong thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->billService->showBill($id);
        if (!$data) {
            return $this->APIError(
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

    public function billWithBillDetail($id)
    {
        $data = $this->billService->eloquentBillWithBillDetail($id);
        if (!$data) {
            return $this->APIError(
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
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
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
        $data = $this->billService->chanceStatusShiping($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
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
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => 'paid',
                ]
            );
        }
    }

    public function pending(string $id)
    {
        $data = $this->billService->chanceStatusPending($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => 'pending',
                ]
            );
        }
    }

    public function done(string $id)
    {
        $data = $this->billService->chanceStatusDone($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => 'done',
                ]
            );
        }
    }

    public function cancel(string $id)
    {
        $data = $this->billService->chanceStatusCancel($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => 'cancel',
                ]
            );
        }
    }

    public function getPaid()
    {
        $data = $this->billService->getStatusPaid();
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getCancel()
    {
        $data = $this->billService->getStatusCancel();
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getShiping()
    {
        $data = $this->billService->getStatusShiping();
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getPending()
    {
        $data = $this->billService->getStatusPending();
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getDone()
    {
        $data = $this->billService->getStatusDone();
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getConfirm()
    {
        $data = $this->billService->getStatusConfirm();
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getBillWithUser($id)
    {
        $data = $this->billService->billWithUser($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('user khong co bill'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getBillWithUserPending($id)
    {
        $data = $this->billService->billWithUsePending($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('user khong co bill'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getBillWithUserCancel($id)
    {
        $data = $this->billService->billWithUseCancel($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('user khong co bill'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getBillWithUserConfirm($id)
    {
        $data = $this->billService->billWithUseConfirm($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('user khong co bill'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getBillWithUserShiping($id)
    {
        $data = $this->billService->billWithUseShiping($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('user khong co bill'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getBillWithUserDone($id)
    {
        $data = $this->billService->billWithUseDone($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('user khong co bill'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getBillWithUserPaid($id)
    {
        $data = $this->billService->billWithUsePaid($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('user khong co bill'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getBillWithphone($phone)
    {
        $data = $this->billService->billFinterWithPhone($phone);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('user khong co bill'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function getBillWithEmail($email)
    {
        $data = $this->billService->billFinterWithEmail($email);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_BAD_REQUEST,
                __('user khong co bill'),
            );
        } else {
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function billStoryWithBill($bill_id)
    {
        $data = $this->billService->getBillStoryWithBill($bill_id);
       if (!$data) {
        return $this->responseNotFound(
            Response::HTTP_NOT_FOUND,
            __('khong tim thay danh muc'));
        }else{
        return $this->responseSuccess(
            __('hien thi danh muc thanh cong'),
          [
              'data' => $data,
          ]
        );
        }
    }

}
