<?php

namespace App\Services;

use App\Models\AttributeName;

class AttributeNameService extends AbstractServices
{
    public function __construct(AttributeName $attributeName)
    {
        Parent::__construct($attributeName);
    }

    public function getAllAttributeName()
    {
        return $this->eloquentGetAll();
    }

    public function storeAttributeName($data)
    {
        return $this->eloquentPostCreate($data);
    }


    public function getAttributeNameById($id)
    {
        return AttributeName::with('attributeValues')->findOrFail($id);
    }

    public function updateAttributeName($id, $data)
    {
        $attribute = AttributeName::findOrFail($id);
        $attribute->update($data);
        return $attribute;
    }

    public function deleteAttributeName($id)
    {
        $attribute = AttributeName::findOrFail($id);
        $attribute->delete();
    }
}
