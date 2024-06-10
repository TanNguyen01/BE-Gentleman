<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    const Enable = 0;
    const Disable = 1;

    protected $fillable = [
        'voucher_code',
        'discount_amount',
        'expiration_date',
        'minimum_purchase',
        'usage_limit',
        'created_at',
        'updated_at',
        'status',
        'description',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
