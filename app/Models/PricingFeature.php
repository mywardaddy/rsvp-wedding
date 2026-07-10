<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingFeature extends Model
{
    protected $fillable = [
        'pricing_package_id', 'name', 'is_included', 'sort_order',
    ];

    protected $casts = [
        'is_included' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(PricingPackage::class, 'pricing_package_id');
    }
}
