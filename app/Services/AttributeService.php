<?php

namespace App\Services;

use App\Models\Attribute;
use App\Traits\APIResponse;

class AttributeService extends AbstractServices
{
    use APIResponse;

    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute);
    }
    public function getAttributes()
    {
        return Attribute::all();
    }

    public function showAttribute($id)
    {
        return $this->find($id);
    }

    public function storeAttribute($data)
    {
        return $this->postCreate($data);
    }

    public function updateAttribute($id, $data)
    {
        return $this->update($id, $data);
    }

    public function destroyAttribute($id)
    {
        return $this->delete($id);
    }
}
