<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Event;

/**
 * Trait to resolve the active Event for the current request.
 *
 * - Pengantin users: returns their own event (via clientEvent relationship).
 * - Superadmin users: returns the event stored in session('managed_event_id'),
 *   allowing them to manage any client's workspace.
 */
trait ResolvesClientEvent
{
    protected function resolveEvent(): ?Event
    {
        $user = auth()->user();

        if ($user->isSuperadmin()) {
            $eventId = session('managed_event_id');

            abort_unless($eventId, 403, 'Silakan pilih client yang ingin dikelola terlebih dahulu.');

            return Event::findOrFail($eventId);
        }

        // Default behavior for pengantin
        $event = $user->clientEvent;

        // If event is unconfigured, return null so dashboard can handle it
        if ($event && !$event->isConfigured()) {
            return null;
        }

        return $event;
    }
}
