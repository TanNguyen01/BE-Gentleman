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
        return $this->responseCreated(__
        ('tao danh muc thanh cong'),
        [
            'data' => $data,
        ]);
    }

    public function store(BillRequest $request)
    {
        $request = $request->validated();
        $data = $this->billService->storeBill($request);
        return $this->responseCreated(__
            ('tao danh muc thanh cong'),
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
    public function update(BillRequest $request, string $id)
    {
        $data = $this->billService->updateBill($id,$request);
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
        $data = $this->billService->destroyBill($id);
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
