<?php

namespace App\Services;

use App\Models\Category;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    public function getAllCategories()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $products = $category->products;
            $totalProducts = 0;
            foreach ($products as $product) {
                $totalProducts += $product->variants->sum('quantity');
            }
            $category->quantity = $totalProducts;
        }
        return $categories;
    }


    public function createCategory($data)
    {
        try {
            DB::beginTransaction();
            $category = Category::create($data);
            DB::commit();
            return $category;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception('Failed to create category value: ' . $e->getMessage());
        }
    }

    public function getCategoryById($id)
    {
        return Category::findOrFail($id);
    }

    public function updateCategory($id, $data)
    {
        $category = Category::findOrFail($id);
        try {
            DB::beginTransaction();
            $category->update($data);
            DB::commit();
            return $category;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception('Failed to update category value: ' . $e->getMessage());
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        try {
            DB::beginTransaction();
            $category->delete();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception('Failed to delete category value: ' . $e->getMessage());
        }
    }
    public function getTotalProductQuantityInCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $totalQuantity = 0;

        foreach ($category->products as $product) {
            foreach ($product->variants as $variant) {
                $totalQuantity += $variant->quantity;
            }
        }

        return $totalQuantity;
    }
    public function getCategoriesByName(string $name)
    {
        try {
            $category = Category::whereNotNull('name')
                ->where('name', 'like', '%' . $name . '%')
                ->get();
            return $category;
        } catch (\Exception $e) {
            Log::error('Error fetching category by name: ' . $e->getMessage());
            throw $e;
        }
    }
}
