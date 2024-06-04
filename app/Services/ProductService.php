<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\APIResponse;

class ProductService
{
    use APIResponse;
    public function getAllProducts()
    {
        return Product::query()->get();
    }

    public function getProductById($id)
    {
        return Product::with('category')->find($id)->toArray();
    }

    public function createProduct($data)
    {
        return Product::create($data);
    }

    public function updateProduct($id, $data)
    {
        return Product::find($id);
    }

    public function deleteProduct($id)
    {
        return Product::find($id);
    }
}
