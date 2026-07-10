<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PricingPackage extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price',
        'discount_type', 'discount_value', 'badge',
        'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'discount_value' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (PricingPackage $package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->name);
            }
        });

        static::updating(function (PricingPackage $package) {
            if ($package->isDirty('name') && !$package->isDirty('slug')) {
                $package->slug = Str::slug($package->name);
            }
        });
    }

    // Relationships
    public function features(): HasMany
    {
        return $this->hasMany(PricingFeature::class)->orderBy('sort_order');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    // Accessors
    public function getDiscountedPriceAttribute(): int
    {
        if ($this->discount_type === 'percentage' && $this->discount_value > 0) {
            return (int) round($this->price * (1 - $this->discount_value / 100));
        }

        if ($this->discount_type === 'fixed' && $this->discount_value > 0) {
            return max(0, $this->price - $this->discount_value);
        }

        return $this->price;
    }

    public function getDiscountAmountAttribute(): int
    {
        return $this->price - $this->discounted_price;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_type !== 'none' && $this->discount_value > 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDiscountedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->discounted_price, 0, ',', '.');
    }

    public function getDiscountLabelAttribute(): string
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        }

        if ($this->discount_type === 'fixed') {
            return 'Rp ' . number_format($this->discount_value, 0, ',', '.');
        }

        return '';
    }

    /**
     * Format any rupiah amount.
     */
    public static function formatRupiah(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
