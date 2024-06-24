<?php

namespace App\Services;

use App\Models\Attribute;

class AttributeService extends AbstractServices
{
    public function __construct(Attribute $attribute)
    {
        Parent::__construct($attribute);
    }

    public function getAllAttribute()
    {
        return $this->eloquentGetAll();
    }

    public function createAttribute($data)
    {
        return $this->eloquentPostCreate($data);
    }


    public function getAttributeById($id)
    {
        return Attribute::with('attributeValues')->findOrFail($id);
    }

    public function updateAttribute($id, $data)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->update($data);
        return $attribute;
    }

    public function deleteAttribute($attribute)
    {
        $attribute->delete();
    }
}
