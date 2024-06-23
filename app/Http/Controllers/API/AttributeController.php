<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AttributeService;
use App\Traits\APIResponse;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AttributeController extends Controller
{
    use ApiResponseTrait;
    use APIResponse;

    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    public function index()
    {
        $attributes = $this->attributeService->getAllAttribute();
        return $this->responseSuccess(
            __('Lấy attribute thành công!'),
            ['attributes' => $attributes]
        );
    }

    public function store(Request $request)
    {
        $attribute = $request->all();
        $attribute = $this->attributeService->createAttribute($attribute);

        return $this->responseCreated(
            __('Tạo thuộc tính thành công!'),
            [
                'attribute' => $attribute
            ]
        );
    }


    public function show(int $id)
    {
        $attribute = $this->attributeService->getAttributeById($id);
        if (!$attribute) {
            return
                $this->responseNotFound(
                    Response::HTTP_NOT_FOUND,
                    __('Không tìm thấy thuộc tính!')

                );
        } else {
            return $this->responseSuccess(
                __('Lấy thuộc tính thành công!'),
                [
                    'attribute' => $attribute,
                ]
            );
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $attribute = $this->attributeService->updateAttribute($id, $data);
        if (!$attribute) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('Không tìm thấy thuộc tính!')
            );
        } else {
            $attribute->update($data);
            return $this->responseSuccess(
                __('Sửa thành công'),
                [
                    'attribute' => $attribute
                ]
            );
        }
    }

    public function destroy(string $id)
    {
        $attribute = $this->attributeService->getAttributeById($id);

        if ($attribute) {
            $this->attributeService->deleteAttribute($attribute);
            return response()->json(['message' => 'Attribute deleted successfully']);
        } else {
            return $this->responseError('Attribute not found', Response::HTTP_NOT_FOUND);
        }
    }
}
