<?php

namespace App\Http\Controllers\Pengantin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ResolvesClientEvent;
use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\ActivityLog;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GuestController extends Controller
{
    use ResolvesClientEvent;

    protected QrCodeService $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function index(Request $request)
    {
        $event = $this->resolveEvent();

        $query = $event->guests()->with(['guestGroup', 'rsvp', 'checkins']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        // Filter by group
        if ($groupId = $request->get('group')) {
            $query->where('guest_group_id', $groupId);
        }

        // Filter by RSVP status
        if ($rsvpStatus = $request->get('rsvp_status')) {
            if ($rsvpStatus === 'belum') {
                $query->whereDoesntHave('rsvp');
            } else {
                $query->whereHas('rsvp', fn($q) => $q->where('status', $rsvpStatus));
            }
        }

        // Filter by checkin status
        if ($checkinStatus = $request->get('checkin_status')) {
            if ($checkinStatus === 'checked_in') {
                $query->whereHas('checkins');
            } else {
                $query->whereDoesntHave('checkins');
            }
        }

        $guests = $query->orderBy('name')->paginate(15)->appends($request->query());
        $groups = $event->guestGroups()->get();

        return view('pengantin.guests.index', compact('guests', 'groups', 'event'));
    }

    public function create()
    {
        $event = $this->resolveEvent();
        $groups = $event->guestGroups()->get();

        return view('pengantin.guests.create', compact('event', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'category' => 'required|in:vip,reguler,keluarga,sahabat',
            'guest_group_id' => 'nullable|exists:guest_groups,id',
            'max_companions' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        $event = $this->resolveEvent();

        $guest = $event->guests()->create($request->only([
            'name', 'phone', 'email', 'address', 'category',
            'guest_group_id', 'max_companions', 'notes',
        ]));

        // Generate QR code
        $this->qrCodeService->generateForGuest($guest);

        ActivityLog::log('create_guest', "Menambahkan tamu: {$guest->name}", $guest);

        return redirect()->route('pengantin.guests.index')
            ->with('success', 'Tamu berhasil ditambahkan!');
    }

    public function show(Guest $guest)
    {
        $this->authorizeGuest($guest);
        $guest->load(['guestGroup', 'rsvp', 'checkins.scanner', 'invitations']);

        $qrSvg = $this->qrCodeService->generateInlineSvg($guest->qr_code, 250);

        return view('pengantin.guests.show', compact('guest', 'qrSvg'));
    }

    public function edit(Guest $guest)
    {
        $this->authorizeGuest($guest);
        $event = $this->resolveEvent();
        $groups = $event->guestGroups()->get();

        return view('pengantin.guests.edit', compact('guest', 'event', 'groups'));
    }

    public function update(Request $request, Guest $guest)
    {
        $this->authorizeGuest($guest);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'category' => 'required|in:vip,reguler,keluarga,sahabat',
            'guest_group_id' => 'nullable|exists:guest_groups,id',
            'max_companions' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        $guest->update($request->only([
            'name', 'phone', 'email', 'address', 'category',
            'guest_group_id', 'max_companions', 'notes',
        ]));

        ActivityLog::log('update_guest', "Mengubah tamu: {$guest->name}", $guest);

        return redirect()->route('pengantin.guests.index')
            ->with('success', 'Data tamu berhasil diperbarui!');
    }

    public function destroy(Guest $guest)
    {
        $this->authorizeGuest($guest);

        $name = $guest->name;
        $guest->delete();

        ActivityLog::log('delete_guest', "Menghapus tamu: {$name}");

        return redirect()->route('pengantin.guests.index')
            ->with('success', 'Tamu berhasil dihapus!');
    }

    public function generateQr(Guest $guest)
    {
        $this->authorizeGuest($guest);

        $guest->update(['qr_code' => 'WG-' . strtoupper(Str::random(10))]);
        $this->qrCodeService->generateForGuest($guest);

        ActivityLog::log('generate_qr', "Generate QR Code untuk: {$guest->name}", $guest);

        return back()->with('success', 'QR Code berhasil di-generate!');
    }

    public function bulkGenerateQr(Request $request)
    {
        $event = $this->resolveEvent();

        $guests = $event->guests()->get();
        foreach ($guests as $guest) {
            $this->qrCodeService->generateForGuest($guest);
        }

        ActivityLog::log('bulk_generate_qr', "Bulk generate QR Code untuk {$guests->count()} tamu");

        return back()->with('success', "QR Code berhasil di-generate untuk {$guests->count()} tamu!");
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:guests,id']);

        $event = $this->resolveEvent();
        $count = $event->guests()->whereIn('id', $request->ids)->delete();

        ActivityLog::log('bulk_delete_guests', "Menghapus {$count} tamu");

        return response()->json(['message' => "{$count} tamu berhasil dihapus!"]);
    }

    private function authorizeGuest(Guest $guest): void
    {
        $event = $this->resolveEvent();
        abort_unless($guest->event_id === $event->id, 403);
    }
}
