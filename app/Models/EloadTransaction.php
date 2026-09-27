<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloadTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'customer_id',
        'mobile_number',
        'network',
        'product_name',
        'amount',
        'reference_number',
        'status',
        'sold_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'sold_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
