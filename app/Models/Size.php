<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'size_name',
    ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'size_attributes', 'size_id', 'attribute_id')
            ->withPivot('attribute_value');
    }
}
