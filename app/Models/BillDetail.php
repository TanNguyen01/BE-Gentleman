<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'attribute',
        'price',
        'quantity',
        'bill_id',
        'voucher',
        'image'
    ];
    public function bill()
    {
        return $this->belongsTo(Bill::class,'bill_id');
    }
}
