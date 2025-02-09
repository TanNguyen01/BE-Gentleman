<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\OrderDetailRequest;
use App\Traits\APIResponse;
use Illuminate\Http\Response;
use App\Services\OrderDetailService;
use Illuminate\Http\Request;

class OrderDetailController extends OrderDetailService
{
    use APIResponse;
    protected $orderDetailService;
    function __construct(OrderDetailService $orderDetailService)
    {
        $this->orderDetailService = $orderDetailService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->orderDetailService->getAllOrderDetail();
        return $this->responseCreated(__
        ('tao danh muc thanh cong'),
        [
            'data' => $data,
        ]);
    }

    public function store(OrderDetailRequest $request)
    {
        $request = $request->all();
        $data = $this->orderDetailService->storeOrderDetail($request);
        return $this->responseCreated(__
        ('tao danh muc thanh cong'),
        [
            'data' => $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $data = $this->orderDetailService->showOrderDetail($id);
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

    /**
     * Show the form for editing the specified resource.
     */
    public function update(OrderDetailRequest $request, string $id)
    {
        $data = $this->orderDetailService->updateOrderDetail($id,$request);
        if(!$data){
            return $this->responseNotFound(Response::HTTP_NOT_FOUND,
            __('khong tim thay danh muc'),
            );
        }else{
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->orderDetailService->destroyOrderDetail($id);
        if(!$data){
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        }else{
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }

    public function orderDetailWithVariant(Request $request)
    {
        $param = $request->all();
        $data = $this->orderDetailService->eloquentOrderDetailWithVariant($param);
        if(!$data){
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        }else{
            return $this->responseSuccess(
                __('cap nhat danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }
}
