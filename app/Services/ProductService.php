<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Variant;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getAllProducts()
    {
//        return Product::with('variants.attributeValues')->get();
        return Product::with(['category', 'variants.attributeValues.attribute'])->get();
    }

    public function createProduct($data)
    {
        DB::beginTransaction();

        try {
            $product = Product::create($data);

            $variants = [];

            foreach ($data['variants'] as $variantData) {
                $variantKey = $variantData['attribute_values'];
                unset($variantData['attribute_values']);

                $variants[serialize($variantKey)][] = $variantData;
            }

            foreach ($variants as $variantKey => $variantDataArray) {
                $quantity = 0;

                foreach ($variantDataArray as $variantData) {
                    $quantity += $variantData['quantity'];
                }

                $variantData = reset($variantDataArray);
                $variant = $product->variants()->create([
                    'price' => $variantData['price'],
                    'quantity' => $quantity,
                ]);

                foreach (unserialize($variantKey) as $attributeValueData) {
                    $attributeValue = AttributeValue::firstOrCreate([
                        'attribute_id' => $attributeValueData['attribute_id'],
                        'value' => $attributeValueData['value'],
                    ]);

                    $variant->attributeValues()->attach($attributeValue->id);
                }
            }

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getProductById($id)
    {
        return Product::with('variants.attributeValues')->findOrFail($id);
    }

    public function updateProduct($id, $data)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);
            $product->update($data);

            if (isset($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    if (isset($variantData['id'])) {
                        $variant = Variant::findOrFail($variantData['id']);
                        $variant->update([
                            'price' => $variantData['price'],
                            'quantity' => $variantData['quantity'],
                        ]);

                        if (isset($variantData['attribute_values'])) {
                            $variant->attributeValues()->sync([]);

                            foreach ($variantData['attribute_values'] as $attributeValueData) {
                                $attributeValue = AttributeValue::firstOrCreate([
                                    'attribute_id' => $attributeValueData['attribute_id'],
                                    'value' => $attributeValueData['value'],
                                ]);

                                $variant->attributeValues()->attach($attributeValue->id);
                            }
                        }
                    } else {
                        $variant = $product->variants()->create([
                            'price' => $variantData['price'],
                            'quantity' => $variantData['quantity'],
                        ]);

                        if (isset($variantData['attribute_values'])) {
                            foreach ($variantData['attribute_values'] as $attributeValueData) {
                                $attributeValue = AttributeValue::firstOrCreate([
                                    'attribute_id' => $attributeValueData['attribute_id'],
                                    'value' => $attributeValueData['value'],
                                ]);

                                $variant->attributeValues()->attach($attributeValue->id);
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


    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }
}
