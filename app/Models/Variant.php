<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'price',
        'price_promotional',
        'quantity',
    ];


    public function attributeNames()
    {
        return $this->belongsToMany(AttributeValue::class, 'variant_attributes', 'variant_id', 'attribute_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'variant_attributes', 'variant_id', 'attribute_id')
            ->withTimestamps();
    }
}
