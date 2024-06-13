<?php

namespace App\Services;

use App\Models\Attribute;
use Illuminate\Support\Facades\DB;

class AttributeService
{
    public function getAllAttributes()
    {
        return Attribute::with('attributeValues')->get();
    }

    public function createAttribute($data)
    {
        DB::beginTransaction();

        try {
            $attribute = Attribute::create($data);
            DB::commit();
            return $attribute;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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

    public function deleteAttribute($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();
    }
}
