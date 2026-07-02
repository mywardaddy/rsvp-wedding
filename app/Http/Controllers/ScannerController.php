<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Checkin;
use App\Models\Scanner;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $scanner = Scanner::where('user_id', $user->id)->where('is_active', true)->with('event')->first();

        if (!$scanner) {
            return view('scanner.no-event');
        }

        $event = $scanner->event;

        // Stats
        $totalGuests = $event->guests()->count();
        $checkedIn = $event->guests()->whereHas('checkins')->count();
        $checkedInToday = $event->guests()->whereHas('checkins', fn($q) => $q->whereDate('checked_in_at', today()))->count();

        // Recent scans
        $recentScans = Checkin::whereHas('guest', fn($q) => $q->where('event_id', $event->id))
            ->where('scanner_user_id', $user->id)
            ->with('guest')
            ->latest('checked_in_at')
            ->take(10)
            ->get();

        return view('scanner.index', compact('event', 'totalGuests', 'checkedIn', 'checkedInToday', 'recentScans'));
    }

    public function scan(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);

        $user = auth()->user();
        $scanner = Scanner::where('user_id', $user->id)->where('is_active', true)->with('event')->firstOrFail();

        $guest = Guest::where('qr_code', $request->qr_code)
            ->where('event_id', $scanner->event_id)
            ->with(['rsvp', 'guestGroup', 'checkins'])
            ->first();

        if (!$guest) {
            // Check if the QR code exists in another event to give a better error message
            $existsElsewhere = Guest::where('qr_code', $request->qr_code)->exists();

            ActivityLog::log('scan_invalid', "Scan QR tidak valid: {$request->qr_code}");

            return response()->json([
                'valid' => false,
                'message' => $existsElsewhere
                    ? 'QR Code bukan milik event ini!'
                    : 'QR Code tidak terdaftar pada event ini!',
                'icon' => '🔴',
            ], 404);
        }

        // Check if already checked in
        if ($guest->checkins()->exists()) {
            $lastCheckin = $guest->checkins()->latest('checked_in_at')->first();

            return response()->json([
                'valid' => false,
                'message' => 'Tamu sudah check-in sebelumnya!',
                'icon' => '🔴',
                'guest' => [
                    'name' => $guest->name,
                    'category' => $guest->category_label,
                    'checked_in_at' => $lastCheckin->checked_in_at->format('H:i'),
                ],
            ], 409);
        }

        // Perform check-in
        $checkin = Checkin::create([
            'guest_id' => $guest->id,
            'scanner_user_id' => $user->id,
            'checked_in_at' => now(),
            'method' => 'qr_scan',
        ]);

        ActivityLog::log('scan_checkin', "Check-in tamu: {$guest->name}", $checkin);

        return response()->json([
            'valid' => true,
            'message' => 'Silakan Masuk!',
            'icon' => '🟢',
            'guest' => [
                'name' => $guest->name,
                'category' => $guest->category_label,
                'group' => $guest->guestGroup?->name ?? '-',
                'companions' => $guest->rsvp?->number_of_guests ?? $guest->max_companions,
                'checked_in_at' => now()->format('H:i'),
            ],
        ]);
    }

    public function manualSearch(Request $request)
    {
        $user = auth()->user();
        $scanner = Scanner::where('user_id', $user->id)->where('is_active', true)->firstOrFail();

        $search = $request->get('q');

        $guests = Guest::where('event_id', $scanner->event_id)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('qr_code', 'like', "%{$search}%");
            })
            ->with(['rsvp', 'checkins', 'guestGroup'])
            ->take(10)
            ->get()
            ->map(fn($guest) => [
                'id' => $guest->id,
                'name' => $guest->name,
                'phone' => $guest->phone,
                'category' => $guest->category_label,
                'group' => $guest->guestGroup?->name ?? '-',
                'is_checked_in' => $guest->is_checked_in,
                'qr_code' => $guest->qr_code,
            ]);

        return response()->json($guests);
    }

    public function manualCheckin(Request $request)
    {
        $request->validate(['guest_id' => 'required|exists:guests,id']);

        $user = auth()->user();
        $scanner = Scanner::where('user_id', $user->id)->where('is_active', true)->firstOrFail();

        $guest = Guest::where('id', $request->guest_id)
            ->where('event_id', $scanner->event_id)
            ->firstOrFail();

        if ($guest->checkins()->exists()) {
            return response()->json([
                'valid' => false,
                'message' => 'Tamu sudah check-in sebelumnya!',
            ], 409);
        }

        $checkin = Checkin::create([
            'guest_id' => $guest->id,
            'scanner_user_id' => $user->id,
            'checked_in_at' => now(),
            'method' => 'manual',
        ]);

        ActivityLog::log('manual_checkin', "Manual check-in: {$guest->name}", $checkin);

        return response()->json([
            'valid' => true,
            'message' => 'Check-in berhasil!',
            'guest' => [
                'name' => $guest->name,
                'category' => $guest->category_label,
            ],
        ]);
    }
}
