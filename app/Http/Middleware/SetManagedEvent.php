<?php

namespace App\Http\Middleware;

use App\Models\Event;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware that ensures a Superadmin has selected a client (event)
 * before accessing Pengantin routes.
 *
 * For non-superadmin users (pengantin), this middleware is a no-op.
 */
class SetManagedEvent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Only applies to superadmin users
        if (!$user || !$user->isSuperadmin()) {
            return $next($request);
        }

        $eventId = session('managed_event_id');

        if (!$eventId) {
            return redirect()
                ->route('admin.clients.index')
                ->with('info', 'Silakan pilih client yang ingin dikelola terlebih dahulu.');
        }

        // Validate that the event still exists
        $event = Event::find($eventId);
        if (!$event) {
            session()->forget('managed_event_id');
            return redirect()
                ->route('admin.clients.index')
                ->with('error', 'Client tidak ditemukan. Silakan pilih ulang.');
        }

        // Store event in request attributes for easy access
        $request->attributes->set('managed_event', $event);

        return $next($request);
    }
}
