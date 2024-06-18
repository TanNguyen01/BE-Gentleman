<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeName;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Variant;
use App\Traits\APIResponse;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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
        return Product::with('sales', 'category', 'variants.attributeName')->get();
    }

    public function showProduct($id)
    {
        return Product::with('sales', 'category', 'variants.attributeName')->find($id);
    }

    public function storeProductVariants(array $productData, $variantData)
    {
        // Tạo sản phẩm
        $product = Product::create([
            'name' => $productData['name'],
            'brand' => $productData['brand'],
            'description' => $productData['description'],
            'image' => $productData['image'],
            'category_id' => $productData['category_id'],
            'sale_id' => $productData['sale_id'],
        ]);

        // Tạo các variant và thuộc tính liên quan nếu có
        if (isset($productData['variants']) && is_array($productData['variants'])) {
            foreach ($productData['variants'] as $variantData) {
                // Tạo variant
                $variant = Variant::create([
                    'product_id' => $product->id,
                    'price' => $variantData['price'],
                    'price_promotional' => $variantData['price_promotional'],
                    'quantity' => $variantData['quantity'],
                ]);

                // Tạo các thuộc tính cho variant nếu có
                if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                    foreach ($variantData['attributes'] as $attribute) {
                        // Tạo attribute_name nếu chưa tồn tại
                        $attributeName = AttributeName::firstOrCreate(['name' => $attribute['name']]);

                        // Tạo attribute_value
                        $attributeValue = AttributeValue::create([
                            'attribute_name_id' => $attributeName->id,
                            'name' => $attribute['value'],
                        ]);

                        // Kết nối variant với attribute_value thông qua pivot table (variant_attribute)
                        $variant->attributeValues()->attach($attributeValue->id);
                    }
                }
            }
        }

        return $product;
    }

    public function updateProduct($id, $data)
    {
        DB::beginTransaction();

        try {
            // Lấy thông tin sản phẩm cần cập nhật
            $product = Product::findOrFail($id);

            // Cập nhật thông tin sản phẩm
            $product->update([
                'name' => $data['name'],
                'brand' => $data['brand'],
                'image' => $data['image'] ?? $product->image,
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'sale_id' => $data['sale_id'],
            ]);
            // Kiểm tra và xử lý tệp ảnh mới
            if (isset($data['new_image']) && $data['new_image'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $data['new_image']->store('image', 'public');
                $product->image = $imagePath;
                $product->save();
            }

            // Kiểm tra và cập nhật các biến thể (variants)
            if (isset($data['variants']) && is_array($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    // Lấy thông tin biến thể hoặc tạo mới nếu chưa tồn tại
                    $variant = Variant::updateOrCreate(
                        ['id' => $variantData['id']],
                        [
                            'product_id' => $product->id,
                            'price' => $variantData['price'],
                            'price_promotional' => $variantData['price_promotional'],
                            'quantity' => $variantData['quantity'],
                        ]
                    );

                    // Xóa hết các thuộc tính của biến thể
                    $variant->attributes()->delete();

                    // Cập nhật hoặc thêm mới các thuộc tính của biến thể
                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attributeData) {
                            $attribute = Attribute::updateOrCreate(
                                ['product_id' => $product->id, 'name' => $attributeData['name']],
                                ['product_id' => $product->id]
                            );
                            AttributeValue::updateOrCreate([
                                'attribute_id' => $attribute->id,
                                'variant_id' => $variant->id,
                                'name' => $attributeData['value'],
                            ]);
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
