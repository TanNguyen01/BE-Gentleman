<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomAttributeVariant extends Model
{
    use HasFactory;

    protected $table = 'custom_attribute_variant_table';

    protected $fillable = [
        'variant_id',
        'attribute_id',
    ];
}
