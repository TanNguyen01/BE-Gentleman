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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_values')
            ->withPivot('name');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
