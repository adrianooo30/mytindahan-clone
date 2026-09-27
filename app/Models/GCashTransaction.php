<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GCashTransaction extends Model
{
    use HasFactory;

    protected $table = 'gcash_transactions';

    protected $fillable = [
        'store_id',
        'type',
        'amount',
        'reference_number',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'integer',
        'transaction_date' => 'date',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
