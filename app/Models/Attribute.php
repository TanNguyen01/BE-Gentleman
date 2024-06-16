<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_id',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }
    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'attribute_values')
            ->withPivot('name');
    }
}
