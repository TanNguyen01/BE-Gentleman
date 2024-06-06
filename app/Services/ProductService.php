<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\APIResponse;

class ProductService extends AbstractServices
{
    use APIResponse;

    public function __construct(Product $product)
    {
        Parent::__construct($product);
    }

    public function getProducts()
    {
        return $this->getAll();
    }

    public function showProduct($id)
    {
        return Product::with('category')->find($id)->toArray();
    }

    public function storeProduct($data)
    {
        return $this->postCreate($data);
    }

    public function updateProduct($id, $data)
    {
        return $this->update($id, $data);
    }

    public function destroyProduct($id)
    {
        return $this->delete($id);
    }
}
