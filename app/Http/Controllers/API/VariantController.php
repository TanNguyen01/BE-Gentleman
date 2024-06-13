<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\VariantsRequest;
use App\Services\VariantService;
use App\Traits\APIResponse;
use Illuminate\Http\Response;

class VariantController extends Controller
{
    use APIResponse;

    protected $variantService;


    public function __construct(VariantService $variantService)
    {
        $this->variantService = $variantService;
    }

    public function index()
    {
        $variant = $this->variantService->getVariant();
        return $this->responseSuccess(
            __('Lấy danh sách thành công!'),
            [
                'data' => $variant,
            ]
        );
    }

    public function store(VariantsRequest $request)
    {
        $data = $request->all();
        $variant = $this->variantService->storeVariant($data);
        return $this->responseCreated(
            __('Tao bien the thanh cong!'),
            [
                'data' => $variant
            ]
        );
    }

    public function show(String $id)
    {
        $variant = $this->variantService->showVariant($id);
        if (!$variant) {
            return
                $this->responseNotFound(
                    Response::HTTP_NOT_FOUND,
                    __('khong tim thay bien the!'),
                    [
                        'data' => $variant,
                    ]
                );
        } else {
            return $this->responseSuccess(
                __('lay bien the thanh cong!'),
                [
                    'data' => $variant,
                ]
            );
        }
    }

    public function update(VariantsRequest $request, $id)
    {
        $data = $request->all();
        $variant = $this->variantService->updateVariant($id, $data);
        if (!$variant) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay bien the!'),
                [
                    'data' => $variant
                ]
            );
        } else {
            $variant->update($data);
            return $this->responseSuccess(
                __('sua thanh cong'),
                [
                    'data' => $variant
                ]
            );
        }
    }

    public function destroy(string $id)
    {
        $variant = $this->variantService->destroyVariant($id);
        if (!$variant) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay bien the!')
            );
        } else {
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }
}
