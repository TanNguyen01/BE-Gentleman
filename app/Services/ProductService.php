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
        return Product::with('sales', 'category', 'variants.attributes.colors', 'variants.attributes.sizes')->find($id)->toArray();
    }

    public function storeProductWithVariants(array $productData, array $variantsData)
    {
        DB::beginTransaction();
        try {
            // Kiểm tra và xử lý tệp ảnh nếu có
            $imagePath = $variantData['image'] ?? "";
            if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $variantData['image']->store('image', 'public'); // Lưu ảnh vào thư mục 'storage/app/public/variant_images'
            }
            $productData['image'] = $imagePath;
            $product = $this->eloquentPostCreate($productData);

            foreach ($variantsData as $variantData) {
                $variant = Variant::create([
                    'product_id' => $product->id,
                    'price' => $variantData['price'],
                    'price_promotional' => $variantData['price_promotional'],
                    'quantity' => $variantData['quantity'],
                ]);

                foreach ($variantData['attributes'] as $attributeData) {
                    // Tạo hoặc lấy thuộc tính đã tồn tại
                    $attribute = Attribute::firstOrCreate(
                        ['variant_id' => $variant->id, 'name' => $attributeData['name']],
                        $attributeData
                    );

                    // Cập nhật màu sắc và kích thước
                    if (isset($attributeData['colors'])) {
                        $attribute->colors()->sync($attributeData['colors']);
                    }
                    if (isset($attributeData['sizes'])) {
                        $attribute->sizes()->sync($attributeData['sizes']);
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

            // Kiểm tra và cập nhật các biến thể (variants)
            if (isset($data['variants']) && is_array($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    // Lấy thông tin biến thể hoặc tạo mới nếu chưa tồn tại
                    $variant = Variant::updateOrCreate(
                        ['id' => $variantData['id']],
                        [
                            'product_id' => $id,
                            'price' => $variantData['price'],
                            'price_promotional' => $variantData['price_promotional'],
                            'quantity' => $variantData['quantity'],
                            'image' => $variantData['image'] ?? "",
                        ]
                    );

                    // Kiểm tra và xử lý tệp ảnh mới
                    if (isset($variantData['new_image']) && $variantData['new_image'] instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = $variantData['new_image']->store('variant_images', 'public');
                        $variant->image = $imagePath;
                        $variant->save();
                    }

                    // Xóa hết các thuộc tính của biến thể
                    $variant->attributes()->delete();

                    // Cập nhật hoặc thêm mới các thuộc tính của biến thể
                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attributeData) {
                            $attribute = Attribute::firstOrCreate(
                                ['variant_id' => $variant->id, 'name' => $attributeData['name']],
                                $attributeData
                            );

                            // Cập nhật màu sắc và kích thước
                            if (isset($attributeData['colors'])) {
                                $attribute->colors()->sync($attributeData['colors']);
                            }
                            if (isset($attributeData['sizes'])) {
                                $attribute->sizes()->sync($attributeData['sizes']);
                            }
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
