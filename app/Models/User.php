<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'avatar',
        'is_active',
        'order_id',
        'subscription_status',
        'activated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'activated_at' => 'datetime',
        ];
    }

    // Relationships
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the single event for a pengantin user (1:1 relationship).
     */
    public function clientEvent(): HasOne
    {
        return $this->hasOne(Event::class);
    }

    public function scannerAssignments(): HasMany
    {
        return $this->hasMany(Scanner::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class, 'scanner_user_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Role Helpers
    public function hasRole(string $role): bool
    {
        return $this->role->name === $role;
    }

    public function isSuperadmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    public function isPengantin(): bool
    {
        return $this->hasRole('pengantin');
    }

    public function isPetugasScan(): bool
    {
        return $this->hasRole('petugas_scan');
    }

    public function isTamu(): bool
    {
        return $this->hasRole('tamu');
    }

    /**
     * Check if this pengantin user has a fully configured event.
     */
    public function hasConfiguredEvent(): bool
    {
        $event = $this->clientEvent;
        return $event && $event->isConfigured();
    }

    /**
     * Get the first event for pengantin users.
     */
    public function getEventAttribute(): ?Event
    {
        if ($this->isPengantin()) {
            return $this->clientEvent;
        }
        return null;
    }
}
