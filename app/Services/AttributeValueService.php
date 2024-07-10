<?php

namespace App\Services;

use App\Models\AttributeValue;

class AttributeValueService extends AbstractServices
{
    public function __construct(AttributeValue $attributeValue)
    {
        Parent::__construct($attributeValue);
    }

    public function getAllAttributeValue()
    {
        return AttributeValue::with('attribute')->get();
    }

    public function storeAttributeValue($data)
    {
        return $this->eloquentPostCreate($data);
    }


    public function getAttributeValueById($id)
    {
        return AttributeValue::with('attribute')->findOrFail($id);
    }

    public function updateAttributeValue($id, $data)
    {
        $attribute = AttributeValue::findOrFail($id);
        $attribute->update($data);
        return $attribute;
    }

    public function deleteAttributeValue($id)
    {
        $attribute = AttributeValue::findOrFail($id);
        $attribute->delete();
    }
}
