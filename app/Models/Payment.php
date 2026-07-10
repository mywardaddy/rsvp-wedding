<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'payment_gateway', 'gateway_transaction_id',
        'payment_method', 'payment_channel', 'amount', 'status',
        'gateway_response', 'va_number', 'payment_url',
        'expired_at', 'paid_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'gateway_response' => 'array',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    // Status Helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function getFormattedAmountAttribute(): string
    {
        return PricingPackage::formatRupiah($this->amount);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'success' => 'Berhasil',
            'failed' => 'Gagal',
            'expired' => 'Kedaluwarsa',
            'refunded' => 'Dikembalikan',
            default => ucfirst($this->status),
        };
    }
}
