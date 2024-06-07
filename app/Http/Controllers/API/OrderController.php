<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\OrderRequest;
use App\Traits\APIResponse;
use Illuminate\Http\Response;
use App\Services\OrderService;

class OrderController extends OrderService
{
    use APIResponse;
    protected $orderService;
    function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->orderService->getAllOrder();
        return $this->responseCreated(__
        ('tao danh muc thanh cong'),
        [
            'data' => $data,
        ]);
    }

    public function store(OrderRequest $request)
    {
        $request = $request->all();
        $data = $this->orderService->storeOrder($request);
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
       $data = $this->orderService->showOrder($id);
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
    public function update(OrderRequest $request, string $id)
    {
        $data = $this->orderService->updateOrder($id,$request);
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
        $data = $this->orderService->destroyOrder($id);
        if(!$data){
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        }else{
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }
}
