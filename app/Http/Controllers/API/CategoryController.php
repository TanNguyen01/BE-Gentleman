<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        try {
            $categories = $this->categoryService->getAllCategories();
            return $this->successResponse([
                'categories' => CategoryResource::collection($categories),
            ], 'Get All Categories');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:categories',
            ]);

            $category = $this->categoryService->createCategory($data);
            return $this->successResponse(new CategoryResource($category), 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            return $this->successResponse(new CategoryResource($category));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
            ]);

            $category = $this->categoryService->updateCategory($id, $data);
            return $this->successResponse(new CategoryResource($category));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);
            return $this->successResponse('Category deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
    public function totalProducts($id)
    {
        $totalQuantity = $this->categoryService->getTotalProductQuantityInCategory($id);
        return response()->json(['total_quantity' => $totalQuantity]);
    }
}
