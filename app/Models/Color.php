<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'color_name',
    ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'color_attributes', 'color_id', 'attribute_id')
            ->withPivot('attribute_value');
    }
}
