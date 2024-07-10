<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillStory extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'bill_id',
        'user_id',
        'description'
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
