<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{

    const Enable = 0;
    const Disable = 1;

    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'price',
        'price_promotional',
        'quantity',
        'description',
        'image',
    ];

    public function toArray()
    {
        $data = [
            'id' => $this->id,
            'product_id' => $this->product ? [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'brand' => $this->product->brand,
            ] : null,
            'attribute_id' => $this->attribute ? [
                'id' => $this->attribute->id,
                'name' => $this->attribute->name,
                'value' => $this->attribute->value,
            ] : null,
            'price' => $this->price,
            'price_promotional' => $this->price_promotional,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'image' => $this->image,
        ];

        return $data;
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
