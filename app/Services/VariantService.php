<?php

namespace App\Services;

use App\Models\Variant;
use App\Traits\APIResponse;

class  VariantService
{

    use APIResponse;
    public function getAllVariants()
    {
        return Variant::query()->get();
    }


    public function getVariantById($id)
    {
        $variant = Variant::find($id);
        if ($variant) {
            $variant->load('product', 'attribute');
            return $variant->toArray();
        } else {
            return null;
        }
    }

    public function createVariant($data)
    {
        return Variant::create($data);
    }

    public function updateVariant($id, $data)
    {
        return Variant::find($id);
    }
    public function deleteVariant($id)
    {
        return Variant::find($id);
    }
}
