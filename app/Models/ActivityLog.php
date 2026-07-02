<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'description', 'model_type', 'model_id',
        'properties', 'ip_address', 'user_agent', 'browser', 'device',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        string $action,
        string $description,
        ?Model $model = null,
        ?array $properties = null
    ): static {
        $request = request();

        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->getKey(),
            'properties' => $properties,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'browser' => static::parseBrowser($request?->userAgent()),
            'device' => static::parseDevice($request?->userAgent()),
        ]);
    }

    private static function parseBrowser(?string $userAgent): ?string
    {
        if (!$userAgent) return null;
        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Edge')) return 'Edge';
        if (str_contains($userAgent, 'Opera')) return 'Opera';
        return 'Other';
    }

    private static function parseDevice(?string $userAgent): ?string
    {
        if (!$userAgent) return null;
        if (str_contains($userAgent, 'Mobile')) return 'Mobile';
        if (str_contains($userAgent, 'Tablet')) return 'Tablet';
        return 'Desktop';
    }
}
