<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AttributeNameService;
use App\Traits\APIResponse;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AttributeNameController extends Controller
{
    use ApiResponseTrait;
    use APIResponse;

    protected $attributeNameService;

    public function __construct(AttributeNameService $attributeNameService)
    {
        $this->attributeNameService = $attributeNameService;
    }

    public function index()
    {
        $attributeNames = $this->attributeNameService->getAllAttributeName();
        return $this->responseSuccess(
            __('Lấy attribute thành công!'),
            [
                'attributeName' => $attributeNames,
            ]
        );
    }

    public function store(Request $request)
    {
        $attributeNameData = $request->all();
        $attributeName = $this->attributeNameService->storeAttributeName($attributeNameData);

        return $this->responseCreated(
            __('Tao san attribute thanh cong!'),
            [
                'attribute_Name' => $attributeName
            ]
        );
    }


    public function show(int $id)
    {
        $attributeName = $this->attributeNameService->getAttributeNameById($id);
        if (!$attributeName) {
            return
                $this->responseNotFound(
                    Response::HTTP_NOT_FOUND,
                    __('khong tim thay Name!')

                );
        } else {
            return $this->responseSuccess(
                __('lay Name thanh cong!'),
                [
                    'attribute_Name' => $attributeName,
                ]
            );
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $attributeName = $this->attributeNameService->updateAttributeName($id, $data);
        if (!$attributeName) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay Name!')
            );
        } else {
            $attributeName->update($data);
            return $this->responseSuccess(
                __('sua thanh cong'),
                [
                    'attribute_Name' => $attributeName
                ]
            );
        }
    }

    public function destroy(string $id)
    {
        $attributeName = $this->attributeNameService->deleteAttributeName($id);
        if (!$attributeName) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay Name!')
            );
        } else {
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }
}
