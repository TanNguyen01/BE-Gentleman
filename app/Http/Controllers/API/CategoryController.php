<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategorieRequest;
use App\Services\CategoryService;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{

    use APIResponse;

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryService->getAllCategory();

        return $this->responseSuccess(
            __(' lay danh sach danh muc thanh cong'),
            [
                'categories' => $categories,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategorieRequest $request)
    {
        $data = $request->all();
        $category = $this->categoryService->createCategory($data);

        return $this->responseCreated(
            __('tao danh muc thanh cong'),
            [
                'category' => $category,
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->categoryService->getCategoryById($id);
        if (!$category) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc')
            );
        } else {
            return $this->responseSuccess(
                __('hien thi danh muc thanh cong'),
                [
                    'category' => $category,
                ]
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategorieRequest $request, string $id)
    {
        $data = $request->all();
        $category = $this->categoryService->updateCategory($id, $data);
        if (!$category) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            $category->update($data);
            return $this->responseSuccess(
                __('Cập nhập danh mục thành công'),
                [
                    'category' => $category,
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = $this->categoryService->deleteCategory($id);
        if (!$category) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc'),
            );
        } else {
            $category->delete();
            return $this->responseSuccess(
                __('Xóa danh mục thành công!'),
                [
                    'category' => $category,
                ]
            );
        }
    }
}
