<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'variant_id',
    ];

    public function variants()
    {
        return $this->belongsTo(Variant::class);
    }

    public function attributeNames()
    {
        return $this->belongsTo(Attribute::class);
    }
}
