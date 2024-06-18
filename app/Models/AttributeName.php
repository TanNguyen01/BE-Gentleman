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
        return $this->hasMany(AttributeValue::class, 'attribute_name_id');
    }

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'variant_attributes', 'attribute_id', 'variant_id',);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attributes', 'attribute_id', 'product_id');
    }
}
