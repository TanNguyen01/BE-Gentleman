<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SizeRequest;
use App\Services\SizeService;
use App\Traits\APIResponse;
use Illuminate\Http\Response;

class SizeController extends Controller
{
    use APIResponse;
    protected $sizeService;
    function __construct(SizeService $sizeService)
    {
        $this->sizeService = $sizeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->sizeService->getAllSize();
        return $this->responseCreated(
            __('tao size thanh cong'),
            [
                'data' => $data,
            ]
        );
    }

    public function store(SizeRequest $request)
    {
        $request = $request->validated();
        $data = $this->sizeService->storeSize($request);
        return $this->responseCreated(
            __('tao sze thanh cong'),
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
        $data = $this->sizeService->showSize($id);
        if (!$data) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay size')
            );
        } else {
            return $this->responseSuccess(
                __('hien thi size thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }
    public function update(SizeRequest $request, $id)
    {
        $data = $request->all();
        $size = $this->sizeService->updateSize($id, $data);
        if (!$size) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay size !')
            );
        } else {
            $size->update($data);
            return $this->responseSuccess(
                __('sua thanh cong'),
                [
                    'data' => $size
                ]
            );
        }
    }

    public function destroy(string $id)
    {
        $product = $this->sizeService->destroySize($id);
        if (!$product) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay size!')
            );
        } else {
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }
}
