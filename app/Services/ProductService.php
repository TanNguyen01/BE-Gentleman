<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Traits\APIResponse;
use Exception;
use Illuminate\Http\Request;
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
    private function uploadImage($image, $productName)
    {
        try {
            $fileName = $productName . '_' . time();

            $extension = $image->getClientOriginalExtension();

            $fileNameToStore = $fileName . '.' . $extension;

            $filePath = $image->storeAs('uploads', $fileNameToStore, 's3'); // 's3' là tên của disk được cấu hình trong config/filesystems.php

            $imageUrl = Storage::disk('s3')->url($filePath);

            return $imageUrl;
        } catch (Exception $e) {
            Log::error('Error uploading image: ' . $e->getMessage());
            throw new Exception('Failed to upload image');
        }
    }


    public function getAllProducts()
    {
        return Product::with('sales', 'category', 'variants.attributeValues.attribute')->get();
    }

    public function showProduct($id)
    {
        return Product::with('sales', 'category', 'variants.attributeValues.attribute')->find($id);
    }

    public function createProductWithVariantsAndAttributes(array $productData)
    {
        DB::beginTransaction();
        try {

            // Tạo sản phẩm
            $product = Product::create([
                'name' => $productData['name'] ?? '',
                'brand' => $productData['brand'] ?? '',
                'image' => $productData['image'] ?? '',
                'description' => $productData['description'] ?? '',
                'category_id' => $productData['category_id'] ?? null,
                'sale_id' => $productData['sale_id'] ?? null,
            ]);

            if (isset($productData['image'])) {
                $imageUrl = $this->uploadImage($productData['image'], $product->name);
                $product->image = $imageUrl;
                $product->save();
            }

            if (isset($productData['variants']) && is_array($productData['variants'])) {
                foreach ($productData['variants'] as $variantData) {
                    $attributeValues = collect();

                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attr) {
                            $attribute = Attribute::firstOrCreate(['name' => $attr['name']]);

                            if (isset($attr['value']) && !empty($attr['value'])) {
                                $attributeValue = AttributeValue::firstOrCreate([
                                    'attribute_id' => $attribute->id,
                                    'value' => $attr['value'],
                                ]);

                                $attributeValues->push($attributeValue->id);
                            } else {
                                throw new Exception('Attribute value cannot be null or empty');
                            }
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
                        $existingVariant->quantity += $variantData['quantity'] ?? 0;
                        $existingVariant->save();
                        $variant = $existingVariant;
                    } else {
                        $variant = $product->variants()->create([
                            'price' => $variantData['price'] ?? 0,
                            'price_promotional' => $variantData['price_promotional'] ?? 0,
                            'quantity' => $variantData['quantity'] ?? 0,
                        ]);

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
            $product = Product::findOrFail($productId);

            $product->update([
                'name' => $productData['name'],
                'brand' => $productData['brand'],
                'description' => $productData['description'],
                'image' => $productData['image'] ?? $product->image,
                'category_id' => $productData['category_id'],
                'sale_id' => $productData['sale_id'] ?? null,
            ]);

            $product->variants()->delete();

            if (isset($productData['variants']) && is_array($productData['variants'])) {
                foreach ($productData['variants'] as $variantData) {
                    $attributeValues = collect();

                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attr) {
                            $attribute = Attribute::firstOrCreate(['name' => $attr['name']]);

                            if (isset($attr['value']) && !empty($attr['value'])) {
                                $attributeValue = AttributeValue::firstOrCreate([
                                    'attribute_id' => $attribute->id,
                                    'value' => $attr['value'],
                                ]);

                                $attributeValues->push($attributeValue->id);
                            } else {
                                throw new Exception('Attribute value cannot be null or empty');
                            }
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
                ->with('sales', 'category', 'variants.attributeValues')
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
                ->with('sales', 'category', 'variants.attributeValues')
                ->get();

            return $products;
        } catch (\Exception $e) {
            Log::error('Error fetching products by sale_id: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getProductsByName(string $name)
    {
        try {
            $products = Product::whereNotNull('name')
                ->with('sales', 'category', 'variants.attributeValues')
                ->where('name', 'like', '%' . $name . '%')->get();
            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Error fetching products by name: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getProductsByCategory(string $category)
    {
        try {
            $products = Product::whereHas('category', function ($query) use ($category) {
                $query->where('name', 'like', '%' . $category . '%')
                    ->whereNotNull('name');
            })->with('category', 'sales', 'variants.attributeValues')->get();
            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Error fetching products by name: ' . $e->getMessage());
            throw $e;
        }
    }
}
