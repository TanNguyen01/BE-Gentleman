<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeName extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function attributeValues()
    {
        return $this->hasMany(attributeValue::class);
    }

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'variant_attributes', 'variant_id', 'attribute_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute', 'attribute_id', 'product_id');
    }
}
