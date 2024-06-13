<?php

namespace App\Services;

use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;

class AttributeValueService
{
    public function getAllAttributeValues()
    {
        return AttributeValue::with('attribute')->get();
    }

    public function createAttributeValue($data)
    {
        DB::beginTransaction();

        try {
            $attributeValue = AttributeValue::create($data);
            DB::commit();
            return $attributeValue;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAttributeValueById($id)
    {
        return AttributeValue::with('attribute')->findOrFail($id);
    }

    public function updateAttributeValue($id, $data)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributeValue->update($data);
        return $attributeValue;
    }

    public function deleteAttributeValue($id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributeValue->delete();
    }
}
