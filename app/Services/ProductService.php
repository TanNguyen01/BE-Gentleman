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

    public function getAllProducts()
    {
        return $this->eloquentGetAll();
    }

    public function showProduct($id)
    {
        return Product::with('category', 'variants.attributes')->find($id)->toArray();
    }

    public function storeProductWithVariants(array $productData, array $variantsData)
    {
        DB::beginTransaction();
        try {
            $product = $this->eloquentPostCreate($productData);

            foreach ($variantsData as $variantData) {
                $variant = Variant::create([
                    'product_id' => $product->id,
                    'price' => $variantData['price'],
                    'price_promotional' => $variantData['price_promotional'],
                    'quantity' => $variantData['quantity'],
                    'image' => $variantData['image'],
                ]);

                if (isset($variantData['attribute_id']) && is_array($variantData['attribute_id'])) {
                    foreach ($variantData['attribute_id'] as $attributeData) {
                        $attribute = Attribute::firstOrCreate(
                            ['name' => $attributeData['name'], 'value' => $attributeData['value']],
                            $attributeData
                        );
                        $variant->attributes()->attach($attribute->id);
                    }
                }
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
        DB::beginTransaction();

        try {
            // Lấy thông tin sản phẩm cần cập nhật
            $product = Product::findOrFail($id);

            // Cập nhật thông tin sản phẩm
            $product->update($data);

            // Kiểm tra nếu có dữ liệu biến thể và là một mảng
            if (isset($data['variants']) && is_array($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    // Lấy thông tin biến thể hoặc tạo mới nếu chưa tồn tại
                    $variant = Variant::updateOrCreate(
                        ['id' => $variantData['id']], // Nếu id được cung cấp, sử dụng nó để tìm biến thể cần cập nhật, nếu không, tạo mới
                        [
                            'product_id' => $id, // Sản phẩm mà biến thể thuộc về
                            'price' => $variantData['price'],
                            'price_promotional' => $variantData['price_promotional'],
                            'quantity' => $variantData['quantity'],
                            'image' => $variantData['image'],
                        ]
                    );

                    // Xóa hết các thuộc tính của biến thể
                    $variant->attributes()->detach();

                    // Cập nhật hoặc thêm mới các thuộc tính của biến thể
                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attributeData) {
                            $attribute = Attribute::firstOrCreate(
                                ['name' => $attributeData['name'], 'value' => $attributeData['value']],
                                $attributeData
                            );
                            $variant->attributes()->attach($attribute->id);
                        }
                    }
                }
            }

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function destroyProduct($id)
    {
        return $this->eloquentDelete($id);
    }
}
