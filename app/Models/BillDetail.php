<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'attribute_name',
        'price',
        'quantity',
        'bill_id',
        'voucher',
    ];
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
