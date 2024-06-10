<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'brand',
        'description'
    ];

    public function toArray()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'status' => $this->category->status
            ] : null,
            'brand' => $this->brand,
            'variants' => $this->variants->map(function ($variant) {
                return [
                    "attribute_id" => $variant->attribute_id,
                    "price" => $variant->price,
                    "price_promotional" => $variant->price_promotional,
                    "quantity" => $variant->quantity,
                    "status" => $variant->status,
                    "description" => $variant->description,
                    "image" => $variant->image,
                ];
            })->toArray(),
        ];

        return $data;
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
