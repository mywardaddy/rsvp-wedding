<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'user_id', 'title', 'slug', 'groom_name', 'bride_name',
        'date', 'time_start', 'time_end', 'venue_name', 'venue_address',
        'venue_lat', 'venue_lng', 'description', 'cover_image',
        'love_story', 'gallery', 'music_url', 'theme_color', 'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'love_story' => 'array',
        'gallery' => 'array',
        'is_active' => 'boolean',
        'venue_lat' => 'decimal:7',
        'venue_lng' => 'decimal:7',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function guestGroups(): HasMany
    {
        return $this->hasMany(GuestGroup::class);
    }

    public function scanners(): HasMany
    {
        return $this->hasMany(Scanner::class);
    }

    public function wishMessages(): HasMany
    {
        return $this->hasMany(WishMessage::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    // Computed attributes
    public function getTotalGuestsAttribute(): int
    {
        return $this->guests()->count();
    }

    public function getRsvpCountAttribute(): int
    {
        return $this->guests()->whereHas('rsvp')->count();
    }

    public function getAttendingCountAttribute(): int
    {
        return $this->guests()->whereHas('rsvp', fn($q) => $q->where('status', 'hadir'))->count();
    }

    public function getCheckedInCountAttribute(): int
    {
        return $this->guests()->whereHas('checkins')->count();
    }
}
