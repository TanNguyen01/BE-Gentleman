<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeValueResource;
use App\Services\AttributeValueService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    use ApiResponseTrait;

    protected $attributeValueService;

    public function __construct(AttributeValueService $attributeValueService)
    {
        $this->attributeValueService = $attributeValueService;
    }

    public function index()
    {
        try {
            $attributeValues = $this->attributeValueService->getAllAttributeValues();
            return $this->successResponse(AttributeValueResource::collection($attributeValues));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'attribute_id' => 'required|exists:attributes,id',
                'value' => 'required|string|unique:attribute_values,value,NULL,id,attribute_id,' . $request->attribute_id,
                'quantity' => 'required|integer',
            ]);

            $attributeValue = $this->attributeValueService->createAttributeValue($data);
            return $this->successResponse(new AttributeValueResource($attributeValue), 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $attributeValue = $this->attributeValueService->getAttributeValueById($id);
            return $this->successResponse(new AttributeValueResource($attributeValue));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'attribute_id' => 'exists:attributes,id',
                'value' => 'string|unique:attribute_values,value,' . $id . ',id,attribute_id,' . $request->attribute_id,
                'quantity' => 'integer',
            ]);

            $attributeValue = $this->attributeValueService->updateAttributeValue($id, $data);
            return $this->successResponse(new AttributeValueResource($attributeValue));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->attributeValueService->deleteAttributeValue($id);
            return $this->successResponse('Attribute value deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
