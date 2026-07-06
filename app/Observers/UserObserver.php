<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Auto-create an unconfigured Event when a pengantin account is created.
     */
    public function created(User $user): void
    {
        if (!$this->isPengantinRole($user->role_id)) {
            return;
        }

        // Only create if the user doesn't already have an event
        if ($user->events()->exists()) {
            return;
        }

        $this->createUnconfiguredEvent($user);
    }

    /**
     * Handle the User "updated" event.
     * Sync is_active status to the linked event.
     * If role changes to/from pengantin, handle event creation/cleanup.
     */
    public function updated(User $user): void
    {
        $wasPengantin = $this->isPengantinRole($user->getOriginal('role_id'));
        $isPengantin = $this->isPengantinRole($user->role_id);

        // Role changed TO pengantin — create event if missing
        if (!$wasPengantin && $isPengantin && !$user->events()->exists()) {
            $this->createUnconfiguredEvent($user);
        }

        // Role changed FROM pengantin — delete unconfigured events only
        if ($wasPengantin && !$isPengantin) {
            $user->events()->where('status', 'unconfigured')->delete();
        }

        // Sync is_active to linked event(s) if it changed
        if ($isPengantin && $user->isDirty('is_active')) {
            $user->events()->update(['is_active' => $user->is_active]);
        }
    }

    /**
     * Create an unconfigured event shell for a pengantin user.
     */
    private function createUnconfiguredEvent(User $user): void
    {
        $slug = 'event-' . Str::slug($user->name) . '-' . $user->id;

        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        Event::create([
            'user_id' => $user->id,
            'slug' => $slug,
            'status' => 'unconfigured',
            'is_active' => false,
        ]);
    }

    /**
     * Check if a role_id belongs to the pengantin role.
     */
    private function isPengantinRole(?int $roleId): bool
    {
        if (!$roleId) {
            return false;
        }

        static $pengantinRoleId = null;

        if ($pengantinRoleId === null) {
            $role = Role::where('name', 'pengantin')->first();
            $pengantinRoleId = $role?->id ?? 0;
        }

        return $roleId === $pengantinRoleId;
    }
}
