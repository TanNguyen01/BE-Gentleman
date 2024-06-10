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
        return $this->eloquentFind($id);
    }

    public function storeAttribute($data)
    {
        return $this->eloquentPostCreate($data);
    }

    public function updateAttribute($id, $data)
    {
        return $this->updateAttribute($id, $data);
    }

    public function destroyAttribute($id)
    {
        return $this->multiDelete($id);
    }
}
