<?php

namespace App\Services;

use App\Models\Attribute;
use App\Traits\APIResponse;

class AttributeService
{
    use APIResponse;
    public function getAllAttribute()
    {
        return Attribute::query()->get();
    }

    public function getAttributeById($id)
    {
        return Attribute::find($id);
    }

    public function createAttribute($data)
    {
        return Attribute::create($data);
    }

    public function updateAttribute($id, $data)
    {
        return Attribute::find($id);
    }

    public function deleteAttribute($id)
    {
        return Attribute::find($id);
    }
}
