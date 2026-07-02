<?php

namespace App\Http\Controllers\Pengantin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ResolvesClientEvent;
use App\Models\Scanner;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ScannerManagementController extends Controller
{
    use ResolvesClientEvent;

    /**
     * List all scanners assigned to the current event.
     */
    public function index()
    {
        $event = $this->resolveEvent();

        $scanners = Scanner::where('event_id', $event->id)
            ->with('user')
            ->latest()
            ->get();

        return view('pengantin.scanners.index', compact('event', 'scanners'));
    }

    /**
     * Show form to create a new scanner (creates user + assignment).
     */
    public function create()
    {
        $event = $this->resolveEvent();
        return view('pengantin.scanners.create', compact('event'));
    }

    /**
     * Store a new scanner user and assign to this event.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        $event = $this->resolveEvent();
        $petugasRole = Role::where('name', 'petugas_scan')->firstOrFail();

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $petugasRole->id,
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        // Assign to event
        Scanner::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'is_active' => true,
        ]);

        ActivityLog::log('create_scanner', "Menambahkan petugas scanner: {$user->name} ke event {$event->title}", $user);

        return redirect()->route('pengantin.scanners.index')
            ->with('success', "Petugas Scanner '{$user->name}' berhasil ditambahkan!");
    }

    /**
     * Show form to edit scanner assignment.
     */
    public function edit(Scanner $scanner)
    {
        $event = $this->resolveEvent();
        abort_unless($scanner->event_id === $event->id, 403);

        $scanner->load('user');

        return view('pengantin.scanners.edit', compact('event', 'scanner'));
    }

    /**
     * Update scanner user details.
     */
    public function update(Request $request, Scanner $scanner)
    {
        $event = $this->resolveEvent();
        abort_unless($scanner->event_id === $event->id, 403);

        $user = $scanner->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        ActivityLog::log('update_scanner', "Mengubah petugas scanner: {$user->name}", $user);

        return redirect()->route('pengantin.scanners.index')
            ->with('success', "Data petugas '{$user->name}' berhasil diperbarui!");
    }

    /**
     * Toggle scanner active status.
     */
    public function toggleActive(Scanner $scanner)
    {
        $event = $this->resolveEvent();
        abort_unless($scanner->event_id === $event->id, 403);

        $scanner->update(['is_active' => !$scanner->is_active]);
        $status = $scanner->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::log('toggle_scanner', "Scanner {$scanner->user->name} {$status}", $scanner);

        return back()->with('success', "Petugas '{$scanner->user->name}' berhasil {$status}.");
    }

    /**
     * Reset scanner user password.
     */
    public function resetPassword(Scanner $scanner)
    {
        $event = $this->resolveEvent();
        abort_unless($scanner->event_id === $event->id, 403);

        $scanner->user->update(['password' => Hash::make('password')]);

        ActivityLog::log('reset_scanner_password', "Reset password petugas: {$scanner->user->name}", $scanner);

        return back()->with('success', "Password '{$scanner->user->name}' berhasil direset ke 'password'.");
    }

    /**
     * Remove scanner assignment and optionally delete the user.
     */
    public function destroy(Scanner $scanner)
    {
        $event = $this->resolveEvent();
        abort_unless($scanner->event_id === $event->id, 403);

        $name = $scanner->user->name;
        $user = $scanner->user;

        // Delete the scanner assignment
        $scanner->delete();

        // If user has no other scanner assignments, deactivate the user
        if ($user->scannerAssignments()->count() === 0) {
            $user->update(['is_active' => false]);
        }

        ActivityLog::log('delete_scanner', "Menghapus petugas scanner: {$name}");

        return redirect()->route('pengantin.scanners.index')
            ->with('success', "Petugas '{$name}' berhasil dihapus dari event ini.");
    }
}
