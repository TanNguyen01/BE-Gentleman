<?php

namespace App\Services;

use App\Models\Attribute;
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
        return Product::with('category', 'variants.attribute')->find($id)->toArray();
    }

    public function storeProductWithVariants(array $productData, array $variantsData)
    {
        DB::beginTransaction();
        try {

            $product = $this->eloquentPostCreate($productData);

            foreach ($variantsData as $variantData) {

                if (isset($variantData['attribute_id'])) {

                    $attributeData = $variantData['attribute_id'];
                    $attribute = Attribute::create($attributeData);
                    $variantData['attribute_id'] = $attribute->id;
                }

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
