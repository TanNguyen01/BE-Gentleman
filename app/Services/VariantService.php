<?php
namespace App\Services;

use App\Models\Variant;

class VariantService
{
    public function getAllVariants()
    {
        return Variant::all();
    }

    public function getVariantById($id)
    {
        return Variant::findOrFail($id);
    }

    public function createVariant($data)
    {
        return Variant::create($data);
    }

    public function updateVariant($id, $data)
    {
        $variant = Variant::findOrFail($id);
        $variant->update($data);
        return $variant;
    }

    public function deleteVariant($id)
    {
        $variant = Variant::findOrFail($id);
        $variant->delete();
    }
}
