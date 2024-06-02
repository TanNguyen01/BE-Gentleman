<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

     const Enable = 0;
     const Disable = 1;

    protected $fillable = [
        'name',
        'quantity',
        'status',
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
