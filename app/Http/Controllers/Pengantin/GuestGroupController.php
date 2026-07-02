<?php

namespace App\Http\Controllers\Pengantin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ResolvesClientEvent;
use App\Models\GuestGroup;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class GuestGroupController extends Controller
{
    use ResolvesClientEvent;

    public function index()
    {
        $event = $this->resolveEvent();
        $groups = $event->guestGroups()->withCount('guests')->get();

        return view('pengantin.groups.index', compact('groups', 'event'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
        ]);

        $event = $this->resolveEvent();

        $group = $event->guestGroups()->create($request->only('name', 'description', 'color'));

        ActivityLog::log('create_group', "Menambahkan grup: {$group->name}", $group);

        return back()->with('success', 'Grup berhasil ditambahkan!');
    }

    public function update(Request $request, GuestGroup $group)
    {
        $event = $this->resolveEvent();
        abort_unless($group->event_id === $event->id, 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
        ]);

        $group->update($request->only('name', 'description', 'color'));

        ActivityLog::log('update_group', "Mengubah grup: {$group->name}", $group);

        return back()->with('success', 'Grup berhasil diperbarui!');
    }

    public function destroy(GuestGroup $group)
    {
        $event = $this->resolveEvent();
        abort_unless($group->event_id === $event->id, 403);

        $name = $group->name;
        $group->delete();

        ActivityLog::log('delete_group', "Menghapus grup: {$name}");

        return back()->with('success', 'Grup berhasil dihapus!');
    }
}
