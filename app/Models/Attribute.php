<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'variant_id',
    ];
    public function variants()
    {
        return $this->belongsTo(Variant::class);
    }
    public function colors()
    {
        return $this->belongsToMany(Color::class, "color_attributes");
    }
    public function sizes()
    {
        return $this->belongsToMany(Size::class, "size_attributes");
    }
}
