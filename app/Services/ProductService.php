<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeName;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Variant;
use App\Traits\APIResponse;
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

    public function getAllProducts()
    {

        return Product::with('sales', 'category', 'variants.attributeNames')->get();
    }

    public function showProduct($id)
    {
        return Product::with('sales', 'category', 'variants.attributeNames')->find($id);
    }

    public function createProductWithVariantsAndAttributes(array $productData)
    {
        DB::beginTransaction();
        try {
            // // Upload hình ảnh lên S3 và lấy URL
            // if (isset($productData['image']) && $productData['image']) {
            //     $productData['image'] = $this->uploadImage($productData['image']);
            // }

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
                    // Tạo biến thể
                    $variant = $product->variants()->create([
                        'price' => $variantData['price'] ?? 0,
                        'price_promotional' => $variantData['price_promotional'] ?? 0,
                        'quantity' => $variantData['quantity'] ?? 0
                    ]);

                    // Tạo các giá trị thuộc tính cho biến thể nếu có
                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attribute) {
                            // Tạo tên thuộc tính nếu chưa tồn tại
                            $attributeName = AttributeName::firstOrCreate(['name' => $attribute['name']]);

                            // Tạo giá trị thuộc tính
                            $attributeValue = AttributeValue::firstOrCreate([
                                'attribute_name_id' => $attributeName->id,
                                'value' => $attribute['value'],
                            ]);

                            // Kết nối biến thể với giá trị thuộc tính thông qua bảng pivot (variant_attributes)
                            $variant->attributeNames()->attach($attributeValue->id);
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
                            $variant->attributeNames()->attach($attributeValue);
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

//

    public function filterByPrice(Request $request)
    {

//        $products = $this->eloquentWithRelations(1,['variants']);
//
//        dd($products);
        try {
            $minPrice = (float)$request->input('minPrice', 0);
            $maxPrice = (float)$request->input('maxPrice', PHP_INT_MAX);


            $products = Product::with(['variants' => function ($query) use ($minPrice, $maxPrice) {
                $query->whereBetween('price_promotional', [$minPrice, $maxPrice])
                    ->whereNotNull('price_promotional');
            }])->get();

            // Lọc các sản phẩm có ít nhất một biến thể trong khoảng giá
            $filteredProducts = $products->filter(function ($product) {
                return $product->variants->isNotEmpty();
            });

            return $filteredProducts;

        } catch (\Exception $e) {
            Log::error('Error fetching products by price: ' . $e->getMessage());
        }

    }

    public function filterByColor(Request $request)
    {
        try {

            $color = (string)$request->input('color');

            $products = Product::with(['variants.attributeValues' => function ($query) use ($color) {
                $query->whereHas('attributeName', function ($query) use ($color) {
                    $query->where('name', 'color')
                        ->where('value', $color);
                });
            }])->get();


            $filteredProducts = $products->filter(function ($product) use ($color) {
                return $product->variants->filter(function ($variant) use ($color) {
                    return $variant->attributeValues->filter(function ($attributeValue) use ($color) {
                        return $attributeValue->attributeName->name === 'color' && $attributeValue->value === $color;
                    })->isNotEmpty();
                })->isNotEmpty();
            });

            return $filteredProducts;
        } catch (\Exception $e) {
            Log::error('Error fetching products by color: ' . $e->getMessage());
        }

    }

    public function filterBySize(Request $request)
    {

            try {
                $size = (string)$request->input('size');

                $products = Product::with(['variants.attributeValues' => function ($query) use ($size) {
                    $query->whereHas('attributeName', function ($query) use ($size) {
                        $query->where('name', 'size')
                            ->where('value', $size);
                    });
                }])->get();

                $filteredProducts = $products->filter(function ($product) use ($size) {
                    return $product->variants->filter(function ($variant) use ($size) {
                        return $variant->attributeValues->filter(function ($attributeValue) use ($size) {
                            return $attributeValue->attributeName->name === 'size' && $attributeValue->value === $size;
                        })->isNotEmpty();
                    })->isNotEmpty();
                });

                return $filteredProducts;

            } catch (\Exception $e) {
                Log::error('Error fetching products by size: ' . $e->getMessage());
                return response()->json(['error' => 'An error occurred while fetching products'], 500);
            }

    }

    public function filterByCategory(Request $request){
        try {
            $categoryIds = (array) $request->input('category_id');

            // Truy vấn sản phẩm với điều kiện category_id trong mảng category
            $products = Product::whereHas('category', function ($query) use ($categoryIds) {
                $query->whereIn('id', $categoryIds)
                    ->whereNotNull('id');
            })->with('category')->get();

            // Lọc các sản phẩm có ít nhất một category hợp lệ
            $filteredProducts = $products->filter(function ($product) {
                return $product->category !== null;
            });

            return $filteredProducts;
        } catch (\Exception $e) {
            Log::error('Error fetching products by category: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching products'], 500);
        }
    }

}







