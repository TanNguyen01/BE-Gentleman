<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeName;
use App\Models\AttributeValue;
use App\Models\Variant;
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
            $attributeName = Attribute::where('name', 'color')->first();

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
        $attributeName = Attribute::firstOrCreate(['name' => 'color']);

        //tạo 1 attribute_value cho attribute_name size
        $sizeValue = AttributeValue::create([
            'attribute_name_id' => $attributeName->id,
            'value' => $value
        ]);
        return $sizeValue;
    }

    public function getColorById($color)
    {
        try {
            // Lấy tất cả các attribute_name có tên là 'size'
            $attributeName = AttributeName::where('name', 'color')->first();
            $attributeValue = AttributeValue::where('attribute_name_id', $attributeName->id)

                ->where('value', $color)
                ->get();
            $Variants = Variant::whereHas('attributeValues', function ($query) use ($attributeValue) {
                $query->whereIn('id',  $attributeValue->pluck('id'));

            })->get();

             return $Variants;



            // Lấy tất cả các attribute_values của attribute_name 'size'




        } catch (\Exception $e) {
            Log::error('Error fetching all colors with values: ' . $e->getMessage());
            throw $e;
        }
    }



    public function destroyColor($id)
    {
        return $this->eloquentDelete($id);
    }
}
