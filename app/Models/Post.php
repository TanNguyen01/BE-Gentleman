<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'product_id',
        'description'
    ];

    public function products(){
        return $this->belongsTo(Product::class);
    }

    public function users(){
        return $this->belongsTo(User::class);
    }
}
