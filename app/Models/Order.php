<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'pricing_package_id', 'user_id',
        'customer_name', 'customer_email', 'customer_whatsapp',
        'groom_name', 'bride_name', 'wedding_date',
        'package_name', 'original_price', 'discount_type',
        'discount_value', 'discount_amount', 'total_amount',
        'status', 'paid_at',
    ];

    protected $casts = [
        'wedding_date' => 'date',
        'original_price' => 'integer',
        'discount_value' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    // Relationships
    public function pricingPackage(): BelongsTo
    {
        return $this->belongsTo(PricingPackage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Status Helpers
    public function isPendingPayment(): bool
    {
        return $this->status === 'pending_payment';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    // Formatted Accessors
    public function getFormattedOriginalPriceAttribute(): string
    {
        return PricingPackage::formatRupiah($this->original_price);
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return PricingPackage::formatRupiah($this->total_amount);
    }

    public function getFormattedDiscountAmountAttribute(): string
    {
        return PricingPackage::formatRupiah($this->discount_amount);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_payment' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'cancelled' => 'Dibatalkan',
            'expired' => 'Kedaluwarsa',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending_payment' => 'amber',
            'paid' => 'green',
            'cancelled' => 'red',
            'expired' => 'gray',
            default => 'gray',
        };
    }

    // Helper
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        $number = "INV-{$date}-{$random}";

        // Ensure uniqueness
        while (self::where('order_number', $number)->exists()) {
            $random = strtoupper(substr(uniqid(), -5));
            $number = "INV-{$date}-{$random}";
        }

        return $number;
    }
}
