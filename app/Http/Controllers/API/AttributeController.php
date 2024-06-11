<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeResource;
use App\Services\AttributeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    use ApiResponseTrait;

    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    public function index()
    {
        try {
            $attributes = $this->attributeService->getAllAttributes();
            return $this->successResponse(['attributes' => AttributeResource::collection($attributes)],"Get attributes successfully");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:attributes,name',
                'type' => 'required|string',
            ]);

            $attribute = $this->attributeService->createAttribute($data);
            return $this->successResponse(new AttributeResource($attribute), 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $attribute = $this->attributeService->getAttributeById($id);
            return $this->successResponse(["attributes" => new AttributeResource($attribute)],"Get attribute successfully");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'string|unique:attributes,name,' . $id,
                'type' => 'string',
            ]);

            $attribute = $this->attributeService->updateAttribute($id, $data);
            return $this->successResponse(new AttributeResource($attribute));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->attributeService->deleteAttribute($id);
            return $this->successResponse('Attribute deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
