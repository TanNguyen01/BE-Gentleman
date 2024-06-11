<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    use ApiResponseTrait;

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        try {
            $products = $this->productService->getAllProducts();
            return $this->successResponse(["products" => $products], 'Products retrieved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:products',
                'category_id' => 'required|exists:categories,id',
                'brand' => 'nullable|string',
                'description' => 'nullable|string',
                'variants' => 'required|array',
                'variants.*.price' => 'required|numeric',
                'variants.*.quantity' => 'required|integer',
                'variants.*.attribute_values' => 'required|array',
                'variants.*.attribute_values.*.attribute_id' => 'required|exists:attributes,id',
                'variants.*.attribute_values.*.value' => 'required|string',

            ]);

            $product = $this->productService->createProduct($data);
            return $this->successResponse(new ProductResource($product), 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productService->getProductById($id);
            return $this->successResponse(new ProductResource($product));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'string|unique:products',
                'category_id' => 'exists:categories,id',
                'brand' => 'nullable|string',
                'description' => 'nullable|string',
                'variants' => 'array',
                'variants.*.price' => 'numeric',
                'variants.*.quantity' => 'integer',
                'variants.*.attribute_values' => 'array',
                'variants.*.attribute_values.*.attribute_id' => 'exists:attributes,id',
                'variants.*.attribute_values.*.value' => 'string',
                'variants.*.attributes.*.quantity' => 'required|integer',
            ]);

            $product = $this->productService->updateProduct($id, $data);
            return $this->successResponse(new ProductResource($product));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);
            return $this->successResponse('Product deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
