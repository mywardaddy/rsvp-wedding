<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Guest extends Model
{
    protected $fillable = [
        'event_id', 'guest_group_id', 'name', 'phone', 'email',
        'address', 'category', 'max_companions', 'notes', 'qr_code', 'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (Guest $guest) {
            if (empty($guest->slug)) {
                $guest->slug = Str::slug($guest->name) . '-' . Str::random(6);
            }
            if (empty($guest->qr_code)) {
                $guest->qr_code = 'WG-' . strtoupper(Str::random(10));
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function guestGroup(): BelongsTo
    {
        return $this->belongsTo(GuestGroup::class);
    }

    public function rsvp(): HasOne
    {
        return $this->hasOne(Rsvp::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function latestCheckin(): HasOne
    {
        return $this->hasOne(Checkin::class)->latestOfMany();
    }

    public function getIsCheckedInAttribute(): bool
    {
        return $this->checkins()->exists();
    }

    public function getHasRsvpAttribute(): bool
    {
        return $this->rsvp !== null;
    }

    public function getRsvpStatusAttribute(): ?string
    {
        return $this->rsvp?->status;
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'vip' => 'VIP',
            'reguler' => 'Reguler',
            'keluarga' => 'Keluarga',
            'sahabat' => 'Sahabat',
            default => $this->category,
        };
    }
}
