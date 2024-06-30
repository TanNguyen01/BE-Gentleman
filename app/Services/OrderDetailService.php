<?php

namespace App\Services;

use App\Models\OrderDetail;
use App\Models\Variant;
use Attribute;

class OrderDetailService extends AbstractServices
{
    public function __construct(Variant $cart)
    {
        parent::__construct($cart);
    }

    public function getAllOrderDetail()
    {
        return $this->eloquentGetAll();
    }

    public function storeOrderDetail($data)
    {
        return $this->eloquentMutiInsert($data);
    }

    public function showOrderDetail($id)
    {
        return $this->eloquentFind($id);
    }

    public function updateOrderDetail($id, $data)
    {
        return $this->eloquentUpdate($id, $data);
    }

    public function destroyOrderDetail($id)
    {
        return $this->eloquentDelete($id);
    }

    public function eloquentVariantConvertData($param)
    {
        $data = [];
        foreach ($param['data'] as $key => $value) {
            // L?y th�ng tin c?a variant c�ng v?i c�c m?i quan h? li�n quan
            $variant = $this->eloquentWithRelations($value['variant_id'], ['attributeValues']);

            $data[] = [
                'id' => $variant->id,
                'product_id' => $variant->product_id,
                'price' => $variant->price,
                'price_promotional' => $variant->price_promotional,
                'quantity' => $variant->quantity,
                'attribute_values' => $variant->attributeValues->map(function ($attr) use ($variant) {
                    // T?m t�n attribute t��ng ?ng v?i attribute_name_id
                    $attributeName = $variant->attributeNames->firstWhere('id', $attr->attribute_name_id);

                    return [
                        'id' => $attr->id,
                        'value' => $attr->value,
                        'attribute_name_id' => $attr->attribute_name_id,
                        'attribute_name' => $attributeName ? $attributeName->name : null
                    ];
                })->toArray()
            ];
        }

        return $data;
    }

    public function eloquentOrderDetailWithVariant($param)
    {
        $variant = $this->eloquentVariantConvertData($param);

        $data = [];
        foreach ($param['data'] as $key => $value) {
            // T?m variant t��ng ?ng v?i variant_id
            $currentVariant = collect($variant)->firstWhere('id', $value['variant_id']);

            // N?u kh�ng t?m th?y variant, b? qua
            if (!$currentVariant) {
                continue;
            }

            // Kh?i t?o m?ng �? ch?a c�c attribute �? chuy?n �?i
            $attributes = [];

            foreach ($value['attributes'] as $attr) {
                $attributeNameId = $attr['attribute_name'];
                $attributeValueId = $attr['attribute_value'];

                // T?m gi� tr? attribute t��ng ?ng trong attribute_values
                $attributeValue = collect($currentVariant['attribute_values'])->firstWhere('id', $attributeValueId);

                if ($attributeValue) {
                    $attributes[] = [
                        'attribute_name' => $attributeValue['attribute_name'],
                        'attribute_value' => $attributeValue['value']
                    ];
                } else {
                    // X? l? tr�?ng h?p kh�ng t?m th?y gi� tr? attribute t��ng ?ng
                    $attributes[] = [
                        'attribute_name' => null,
                        'attribute_value' => null
                    ];
                }
            }

            // Th�m d? li?u �? chuy?n �?i v�o m?ng data
            $data[] = [
                'id' => $currentVariant['id'],
                'product_id' => $currentVariant['product_id'],
                'price' => $currentVariant['price'],
                'price_promotional' => $currentVariant['price_promotional'],
                'quantity' => $currentVariant['quantity'],
                'variant_id' => $value['variant_id'], // B? sung variant_id n?u c?n thi?t
                'attributes' => $attributes
            ];
        }



        return $data;
    }
}
