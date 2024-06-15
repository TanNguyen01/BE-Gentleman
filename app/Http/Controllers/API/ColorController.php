<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorRequest;
use App\Traits\APIResponse;
use Illuminate\Http\Response;
use App\Services\ColorService;

class ColorController extends Controller
{
    use APIResponse;
    protected $colorService;
    function __construct(ColorService $billService)
    {
        $this->colorService = $billService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->colorService->getAllColor();
        return $this->responseCreated(
            __('tao mau thanh cong'),
            [
                'data' => $data,
            ]
        );
    }

    public function store(ColorRequest $request)
    {
        $request = $request->validated();
        $data = $this->colorService->storeColor($request);
        return $this->responseCreated(
            __('tao mau thanh cong'),
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
        $data = $this->colorService->showColor($id);
        if (!$data) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay mau')
            );
        } else {
            return $this->responseSuccess(
                __('hien thi mau thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }
    public function update(ColorRequest $request, $id)
    {
        $data = $request->all();
        $color = $this->colorService->updateColor($id, $data);
        if (!$color) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay color !')
            );
        } else {
            $color->update($data);
            return $this->responseSuccess(
                __('sua thanh cong'),
                [
                    'product' => $color
                ]
            );
        }
    }

    public function destroy(string $id)
    {
        $product = $this->colorService->destroyColor($id);
        if (!$product) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay color!')
            );
        } else {
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }
}
