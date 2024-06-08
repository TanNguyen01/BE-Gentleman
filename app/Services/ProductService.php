<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Variant;
use App\Traits\APIResponse;
use Illuminate\Support\Facades\DB;

class ProductService extends AbstractServices
{
    use APIResponse;

    public function __construct(Product $product)
    {
        Parent::__construct($product);
    }

    public function getProducts()
    {
        return $this->eloquentGetAll();
    }

    public function showProduct($id)
    {
        return Product::with('category')->find($id)->toArray();
    }

    public function storeProductWithVariants(array $productData, array $variantsData)
    {
        DB::beginTransaction();
        try {
            // Tạo sản phẩm
            $product = $this->eloquentPostCreate($productData);

            // Lưu các biến thể
            foreach ($variantsData as $variantData) {
                $variantData['product_id'] = $product->id;
                Variant::create($variantData);
            }

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProduct($id, $data)
    {
        return $this->eloquentUpdate($id, $data);
    }

    public function destroyProduct($id)
    {
        return $this->eloquentDelete($id);
    }
}
