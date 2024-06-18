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


    public function attributeName()
    {
        return $this->belongsToMany(AttributeName::class, 'variant_attribute', 'variant_id', 'attribute_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
