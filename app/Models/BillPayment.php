<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'customer_id',
        'biller',
        'account_number',
        'account_name',
        'amount',
        'service_fee',
        'total',
        'reference_number',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'service_fee' => 'integer',
        'total' => 'integer',
        'paid_at' => 'datetime',
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
