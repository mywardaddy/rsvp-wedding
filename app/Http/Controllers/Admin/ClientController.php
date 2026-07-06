<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display all pengantin users as clients.
     * The client list is driven by users with role=pengantin, NOT by events.
     */
    public function index(Request $request)
    {
        $pengantinRole = Role::where('name', 'pengantin')->first();

        if (!$pengantinRole) {
            $clients = collect();
            return view('admin.clients.index', ['clients' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12)]);
        }

        $query = User::where('role_id', $pengantinRole->id)
            ->with(['clientEvent' => function ($q) {
                $q->withCount('guests');
            }]);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('clientEvent', function ($eq) use ($search) {
                      $eq->where('title', 'like', "%{$search}%")
                        ->orWhere('groom_name', 'like', "%{$search}%")
                        ->orWhere('bride_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->get('status') !== '') {
            $status = $request->get('status');

            if ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif (in_array($status, ['unconfigured', 'draft', 'active', 'done'])) {
                $query->where('is_active', true)
                      ->whereHas('clientEvent', fn($q) => $q->where('status', $status));
            }
        }

        $clients = $query->latest()->paginate(12)->appends($request->query());

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the setup form to complete event data for a pengantin user.
     */
    public function setup(User $user)
    {
        abort_unless($user->isPengantin(), 404, 'User bukan akun Pengantin.');

        $event = $user->clientEvent;

        // If no event exists at all, create one
        if (!$event) {
            $slug = 'event-' . Str::slug($user->name) . '-' . $user->id;
            $event = Event::create([
                'user_id' => $user->id,
                'slug' => $slug,
                'status' => 'unconfigured',
                'is_active' => false,
            ]);
        }

        return view('admin.clients.setup', compact('user', 'event'));
    }

    /**
     * Store the completed event setup data.
     */
    public function storeSetup(Request $request, User $user)
    {
        abort_unless($user->isPengantin(), 404, 'User bukan akun Pengantin.');

        $request->validate([
            'groom_name' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'nullable',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string|max:1000',
            'venue_lat' => 'nullable|numeric|between:-90,90',
            'venue_lng' => 'nullable|numeric|between:-180,180',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'theme_color' => 'nullable|string|max:20',
            'status' => 'required|in:draft,active,done',
        ]);

        $event = $user->clientEvent;

        $data = $request->only([
            'groom_name', 'bride_name', 'title', 'date',
            'time_start', 'time_end', 'venue_name', 'venue_address',
            'venue_lat', 'venue_lng', 'theme_color', 'status',
        ]);

        // Generate proper slug
        $slug = Str::slug($data['title'] . '-' . $data['groom_name'] . '-' . $data['bride_name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $data['slug'] = $slug;

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
                Storage::disk('public')->delete($event->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $event->update($data);

        ActivityLog::log(
            'setup_client',
            "Melengkapi data client: {$event->groom_name} & {$event->bride_name}",
            $event
        );

        return redirect()->route('admin.clients.index')->with('success', "Data client {$event->groom_name} & {$event->bride_name} berhasil dilengkapi!");
    }

    /**
     * Show the form for editing an existing client's event.
     */
    public function edit(User $user)
    {
        abort_unless($user->isPengantin(), 404, 'User bukan akun Pengantin.');

        $event = $user->clientEvent;
        abort_unless($event && $event->isConfigured(), 404, 'Event belum dikonfigurasi. Gunakan Lengkapi Data.');

        $user->load('clientEvent');

        return view('admin.clients.edit', compact('user', 'event'));
    }

    /**
     * Update the specified client's event data.
     */
    public function update(Request $request, User $user)
    {
        abort_unless($user->isPengantin(), 404, 'User bukan akun Pengantin.');

        $event = $user->clientEvent;
        abort_unless($event, 404, 'Event tidak ditemukan.');

        $request->validate([
            'groom_name' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'nullable',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string|max:1000',
            'venue_lat' => 'nullable|numeric|between:-90,90',
            'venue_lng' => 'nullable|numeric|between:-180,180',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'theme_color' => 'nullable|string|max:20',
            'status' => 'required|in:draft,active,done',
        ]);

        $data = $request->only([
            'groom_name', 'bride_name', 'title', 'date',
            'time_start', 'time_end', 'venue_name', 'venue_address',
            'venue_lat', 'venue_lng', 'theme_color', 'status',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
                Storage::disk('public')->delete($event->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // Handle cover image removal
        if ($request->has('remove_cover') && $request->remove_cover) {
            if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
                Storage::disk('public')->delete($event->cover_image);
            }
            $data['cover_image'] = null;
        }

        $event->update($data);

        ActivityLog::log(
            'update_client',
            "Mengubah data client: {$event->groom_name} & {$event->bride_name}",
            $event
        );

        return redirect()->route('admin.clients.index')->with('success', 'Data client berhasil diperbarui!');
    }

    /**
     * Toggle active status for a pengantin user and their event.
     */
    public function toggleActive(User $user)
    {
        abort_unless($user->isPengantin(), 404, 'User bukan akun Pengantin.');

        $user->update(['is_active' => !$user->is_active]);
        // Observer will sync event is_active

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLog::log('toggle_client', "Client {$user->name} {$status}", $user);

        return back()->with('success', "Client {$user->name} berhasil {$status}.");
    }

    /**
     * Delete the event data for a pengantin user.
     * The user account is NOT deleted. A new unconfigured event is recreated.
     */
    public function destroy(User $user)
    {
        abort_unless($user->isPengantin(), 404, 'User bukan akun Pengantin.');

        $event = $user->clientEvent;

        if ($event) {
            $groomName = $event->groom_name ?? $user->name;
            $brideName = $event->bride_name ?? '';

            // Delete cover image if exists
            if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
                Storage::disk('public')->delete($event->cover_image);
            }

            // Clear managed event session if this was the active one
            if (session('managed_event_id') == $event->id) {
                session()->forget('managed_event_id');
            }

            $event->delete(); // Cascade deletes guests, invitations, etc.

            ActivityLog::log(
                'delete_client',
                "Menghapus data event client: {$groomName} & {$brideName}"
            );

            // Recreate an unconfigured event shell
            $slug = 'event-' . Str::slug($user->name) . '-' . $user->id . '-' . time();
            Event::create([
                'user_id' => $user->id,
                'slug' => $slug,
                'status' => 'unconfigured',
                'is_active' => false,
            ]);
        }

        return redirect()->route('admin.clients.index')->with('success', "Data event berhasil dihapus. Akun pengantin tetap tersimpan.");
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
