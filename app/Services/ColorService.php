<?php

namespace App\Services;

use App\Models\AttributeName;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Log;

class ColorService extends AbstractServices
{
    public function __construct(AttributeValue $size)
    {
        parent::__construct($size);
    }

    public function getAllColorsWithValues()
    {
        try {
            // Lấy tất cả các attribute_name có tên là 'size'
            $attributeName = AttributeName::where('name', 'color')->first();

            if (!$attributeName) {
                return []; // Nếu không tìm thấy, trả về mảng rỗng hoặc null tùy theo nhu cầu của bạn
            }

            // Lấy tất cả các attribute_values của attribute_name 'size'
            $colors = $attributeName->attributeValues;

            return $colors;
        } catch (\Exception $e) {
            Log::error('Error fetching all colors with values: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createColorValue($value)
    {
        //Tìm hoặc tạo 1 attribute_name có name là size
        $attributeName = AttributeName::firstOrCreate(['name' => 'color']);

        //tạo 1 attribute_value cho attribute_name size
        $sizeValue = AttributeValue::create([
            'attribute_name_id' => $attributeName->id,
            'value' => $value
        ]);
        return $sizeValue;
    }



    public function destroyColor($id)
    {
        return $this->eloquentDelete($id);
    }
}
