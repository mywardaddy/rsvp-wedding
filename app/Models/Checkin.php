<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkin extends Model
{
    protected $fillable = ['guest_id', 'scanner_user_id', 'checked_in_at', 'method', 'notes'];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanner_user_id');
    }
}
