<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeName;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Variant;
use App\Traits\APIResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductService extends AbstractServices
{
    use APIResponse;

    public function __construct(Product $product)
    {
        Parent::__construct($product);
    }

    public function getAllProducts()
    {
        return Product::with('sales', 'category', 'variants.attributeValues.attributeName')->get();
    }

    public function showProduct($id)
    {
        return Product::with('sales', 'category', 'variants.attributeValues.attributeName')->find($id);
    }

    public function createProductWithVariantsAndAttributes(array $productData)
    {
        DB::beginTransaction();
        try {
            // Tạo sản phẩm
            $product = Product::create([
                'name' => $productData['name'] ?? null,
                'brand' => $productData['brand'] ?? null,
                'image' => $productData['image'] ?? null,
                'description' => $productData['description'] ?? null,
                'category_id' => $productData['category_id'] ?? null,
                'sale_id' => $productData['sale_id'] ?? null,
            ]);

            // Tạo các biến thể và giá trị thuộc tính liên quan nếu có
            if (isset($productData['variants']) && is_array($productData['variants'])) {
                foreach ($productData['variants'] as $variantData) {
                    // Tạo một collection để lưu các giá trị thuộc tính
                    $attributeValues = collect();

                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attribute) {
                            // Tạo tên thuộc tính nếu chưa tồn tại
                            $attributeName = AttributeName::firstOrCreate(['name' => $attribute['name']]);

                            // Tạo giá trị thuộc tính
                            $attributeValue = AttributeValue::firstOrCreate([
                                'attribute_name_id' => $attributeName->id,
                                'value' => $attribute['value'],
                            ]);

                            $attributeValues->push($attributeValue->id);
                        }
                    }

                    // Kiểm tra sự tồn tại của biến thể dựa trên giá trị thuộc tính
                    $existingVariant = $product->variants()
                        ->where('price', $variantData['price'] ?? 0)
                        ->where('price_promotional', $variantData['price_promotional'] ?? 0)
                        ->whereHas('attributeValues', function ($query) use ($attributeValues) {
                            $query->whereIn('attribute_value_id', $attributeValues);
                        })->first();

                    if ($existingVariant) {
                        // Cộng dồn số lượng nếu biến thể đã tồn tại
                        $existingVariant->quantity += $variantData['quantity'] ?? 0;
                        $existingVariant->save();
                        $variant = $existingVariant;
                    } else {
                        // Tạo biến thể mới nếu không tồn tại
                        $variant = $product->variants()->create([
                            'price' => $variantData['price'] ?? 0,
                            'price_promotional' => $variantData['price_promotional'] ?? 0,
                            'quantity' => $variantData['quantity'] ?? 0,
                        ]);

                        // Kết nối biến thể với giá trị thuộc tính thông qua bảng pivot (variant_attributes)
                        if ($attributeValues->isNotEmpty()) {
                            $variant->attributeValues()->attach($attributeValues);
                        }
                    }
                }
            }

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product with variants and attributes: ' . $e->getMessage());
            throw $e;
        }
    }





    public function updateProductWithVariantsAndAttributes($productId, array $productData)
    {
        DB::beginTransaction();
        try {
            // Lấy sản phẩm cần cập nhật
            $product = Product::findOrFail($productId);

            // Cập nhật thông tin sản phẩm
            $product->update([
                'name' => $productData['name'],
                'brand' => $productData['brand'],
                'description' => $productData['description'],
                'image' => $productData['image'] ?? $product->image,
                'category_id' => $productData['category_id'],
                'sale_id' => $productData['sale_id'] ?? null,
            ]);

            // Xóa các biến thể cũ và các giá trị thuộc tính của chúng
            $product->variants()->delete();

            // Thêm các biến thể mới và các giá trị thuộc tính tương ứng
            if (isset($productData['variants']) && is_array($productData['variants'])) {
                foreach ($productData['variants'] as $variantData) {
                    // Tạo biến thể mới
                    $variant = $product->variants()->create([
                        'price' => $variantData['price'],
                        'price_promotional' => $variantData['price_promotional'],
                        'quantity' => $variantData['quantity'],
                        'image' => $variantData['image'] ?? null,
                    ]);

                    // Xử lý các giá trị thuộc tính của biến thể mới
                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attribute) {
                            // Tạo hoặc lấy tên thuộc tính
                            $attributeName = AttributeName::firstOrCreate(['name' => $attribute['name']]);

                            // Tạo hoặc lấy giá trị thuộc tính
                            $attributeValue = AttributeValue::firstOrCreate([
                                'attribute_name_id' => $attributeName->id,
                                'value' => $attribute['value'],
                            ]);

                            // Liên kết giá trị thuộc tính với biến thể
                            $variant->attributeValues()->attach($attributeValue);
                        }
                    }
                }
            }

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating product with variants and attributes: ' . $e->getMessage());
            throw $e;
        }
    }

    // public function uploadImage($image)
    // {
    //     try {
    //         $path = $image->store('images', 's3', 'public');
    //         return Storage::disk('s3')->url($path);
    //     } catch (\Exception $e) {
    //         Log::error('Error uploading image to S3: ' . $e->getMessage());
    //         throw $e;
    //     }
    // }

    public function destroyProduct($id)
    {
        return $this->eloquentDelete($id);
    }

    public function getAllWithSale()
    {
        try {
            $products = Product::whereNotNull('sale_id')
                ->with('sales', 'category', 'variants.attributeValues.attributeName')
                ->get();

            return $products;
        } catch (\Exception $e) {
            Log::error('Error fetching products by sale_id: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getProductBySaleId($saleId)
    {
        try {
            $products = Product::where('sale_id', $saleId)
                ->with('sales', 'category', 'variants.attributeValues.attributeName')
                ->get();

            return $products;
        } catch (\Exception $e) {
            Log::error('Error fetching products by sale_id: ' . $e->getMessage());
            throw $e;
        }
    }
}
