<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $fillable = ['event_id', 'key', 'value'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public static function get(string $key, mixed $default = null, ?int $eventId = null): mixed
    {
        $query = static::where('key', $key);
        if ($eventId) {
            $query->where('event_id', $eventId);
        } else {
            $query->whereNull('event_id');
        }
        return $query->first()?->value ?? $default;
    }

    public static function set(string $key, mixed $value, ?int $eventId = null): static
    {
        return static::updateOrCreate(
            ['key' => $key, 'event_id' => $eventId],
            ['value' => $value]
        );
    }
}
