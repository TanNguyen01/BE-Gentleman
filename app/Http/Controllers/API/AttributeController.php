<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributesRequest;
use App\Services\AttributeService;
use App\Traits\APIResponse;
use Illuminate\Http\Response;

class AttributeController extends Controller
{
    use APIResponse;

    protected $attributeService;


    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    public function index()
    {
        $attribute = $this->attributeService->getAttributes();
        return $this->responseSuccess(
            __('Lấy danh sách thành công!'),
            [
                'data' => $attribute,
            ]
        );
    }

    public function store(AttributesRequest $request)
    {
        $data = $request->all();
        $attribute = $this->attributeService->storeAttribute($data);
        return $this->responseCreated(
            __('Tao thuoc tinh thanh cong!'),
            [
                'data' => $attribute
            ]
        );
    }

    public function show(int $id)
    {
        $attribute = $this->attributeService->showAttribute($id);
        if (!$attribute) {
            return
                $this->responseNotFound(
                    Response::HTTP_NOT_FOUND,
                    __('khong tim thay thuoc tinh!'),
                    [
                        'data' => $attribute,
                    ]
                );
        } else {
            return $this->responseSuccess(
                __('lay bien the thanh cong!'),
                [
                    'data' => $attribute,
                ]
            );
        }
    }

    public function update(AttributesRequest $request, $id)
    {
        $data = $request->all();
        $attribute = $this->attributeService->updateAttribute($id, $data);
        if (!$attribute) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay thuoc tinh!'),
                [
                    'data' => $attribute
                ]
            );
        } else {
            $attribute->update($data);
            return $this->responseSuccess(
                __('sua thanh cong'),
                [
                    'data' => $attribute
                ]
            );
        }
    }

    public function destroy(string $id)
    {
        $attribute = $this->attributeService->destroyAttribute($id);
        if (!$attribute) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay bien the!')
            );
        } else {
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }
}
