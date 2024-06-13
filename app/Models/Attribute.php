<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
    ];
    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'custom_attribute_variant_table', 'attribute_id', 'variant_id');
    }
}
