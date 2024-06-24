<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AttributeValueService;
use App\Traits\APIResponse;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AttributeValueController extends Controller
{
    use ApiResponseTrait;
    use APIResponse;

    protected $attributeValueService;

    public function __construct(AttributeValueService $attributeValueService)
    {
        $this->attributeValueService = $attributeValueService;
    }

    public function index()
    {
        $attributeValues = $this->attributeValueService->getAllAttributeValue();
        return $this->responseSuccess(
            __('Lấy attribute thành công!'),
            [
                'attributeValues' => $attributeValues,
            ]
        );
    }

    public function store(Request $request)
    {
        $attributeValue = $request->all();
        $attributeValue = $this->attributeValueService->storeAttributeValue($attributeValue);

        return $this->responseCreated(
            __('Tao san attribute thanh cong!'),
            [
                'attribute_value' => $attributeValue
            ]
        );
    }


    public function show(int $id)
    {
        $attributeValue = $this->attributeValueService->getAttributeValueById($id);
        if (!$attributeValue) {
            return
                $this->responseNotFound(
                    Response::HTTP_NOT_FOUND,
                    __('khong tim thay value!')

                );
        } else {
            return $this->responseSuccess(
                __('lay value thanh cong!'),
                [
                    'attribute_value' => $attributeValue,
                ]
            );
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $attributeValue = $this->attributeValueService->updateAttributeValue($id, $data);
        if (!$attributeValue) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay value!')
            );
        } else {
            $attributeValue->update($data);
            return $this->responseSuccess(
                __('sua thanh cong'),
                [
                    'attribute_value' => $attributeValue
                ]
            );
        }
    }

    public function destroy(string $id)
    {
        $attributeValue = $this->attributeValueService->deleteAttributeValue($id);
        if (!$attributeValue) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay value!')
            );
        } else {
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }
}
