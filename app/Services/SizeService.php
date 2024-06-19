<?php

namespace App\Services;

use App\Models\AttributeName;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Log;

class SizeService extends AbstractServices
{
    public function __construct(AttributeValue $size)
    {
        parent::__construct($size);
    }

    public function getAllSizesWithValues()
    {
        try {
            // Lấy tất cả các attribute_name có tên là 'size'
            $attributeName = AttributeName::where('name', 'size')->first();

            if (!$attributeName) {
                return []; // Nếu không tìm thấy, trả về mảng rỗng hoặc null tùy theo nhu cầu của bạn
            }

            // Lấy tất cả các attribute_values của attribute_name 'size'
            $sizes = $attributeName->attributeValues;

            return $sizes;
        } catch (\Exception $e) {
            Log::error('Error fetching all sizes with values: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createSizeValue($value)
    {
        //Tìm hoặc tạo 1 attribute_name có name là size
        $attributeName = AttributeName::firstOrCreate(['name' => 'size']);

        //tạo 1 attribute_value cho attribute_name size
        $sizeValue = AttributeValue::create([
            'attribute_name_id' => $attributeName->id,
            'value' => $value
        ]);
        return $sizeValue;
    }



    public function destroySize($id)
    {
        return $this->eloquentDelete($id);
    }
}
