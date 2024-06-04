<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'brand'
    ];

    public function toArray()
    {
        $data = [
            'id' => $this->id,
            'category_id' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'status' => $this->category->status
            ] : null,
            'brand' => $this->brand
        ];

        return $data;
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
