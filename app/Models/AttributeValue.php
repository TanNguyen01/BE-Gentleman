<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'attribute_name_id',
    ];

    public function attributeName()
    {
        return $this->belongsTo(AttributeName::class);
    }
    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'variant_attributes', 'attribute_value_id', 'variant_id')->withPivot('variant_id');
    }
}
