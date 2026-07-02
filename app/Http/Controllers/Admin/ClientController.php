<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display all wedding events (clients) for superadmin management.
     */
    public function index(Request $request)
    {
        $query = Event::with('user')->withCount('guests');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('groom_name', 'like', "%{$search}%")
                  ->orWhere('bride_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        $events = $query->latest()->paginate(12)->appends($request->query());

        return view('admin.clients.index', compact('events'));
    }

    /**
     * Set the managed event in session and redirect to pengantin workspace.
     */
    public function manage(Event $event)
    {
        session(['managed_event_id' => $event->id]);

        ActivityLog::log(
            'manage_client',
            "Mulai mengelola client: {$event->groom_name} & {$event->bride_name}",
            $event
        );

        return redirect()->route('pengantin.dashboard');
    }

    /**
     * Clear the managed event from session and return to client list.
     */
    public function switchBack()
    {
        $eventId = session('managed_event_id');
        session()->forget('managed_event_id');

        if ($eventId) {
            $event = Event::find($eventId);
            if ($event) {
                ActivityLog::log(
                    'switch_client',
                    "Berhenti mengelola client: {$event->groom_name} & {$event->bride_name}",
                    $event
                );
            }
        }

        return redirect()->route('admin.clients.index');
    }
}
