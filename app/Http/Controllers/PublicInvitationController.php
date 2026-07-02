<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Rsvp;
use App\Models\WishMessage;
use App\Models\ActivityLog;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class PublicInvitationController extends Controller
{
    public function show(string $slug)
    {
        $guest = Guest::where('slug', $slug)->with(['event', 'rsvp'])->firstOrFail();
        $event = $guest->event;

        abort_unless($event->is_active, 404);

        // Mark invitation as opened
        $guest->invitations()->whereNull('opened_at')->update(['opened_at' => now()]);

        // Get wish messages
        $wishes = $event->wishMessages()
            ->where('is_approved', true)
            ->latest()
            ->take(50)
            ->get();

        return view('invitation.show', compact('guest', 'event', 'wishes'));
    }

    public function rsvp(Request $request, string $slug)
    {
        $guest = Guest::where('slug', $slug)->with('event')->firstOrFail();

        $request->validate([
            'status' => 'required|in:hadir,tidak_hadir',
            'number_of_guests' => 'required_if:status,hadir|integer|min:1|max:' . $guest->max_companions,
            'message' => 'nullable|string|max:500',
        ]);

        $rsvp = Rsvp::updateOrCreate(
            ['guest_id' => $guest->id],
            [
                'status' => $request->status,
                'number_of_guests' => $request->status === 'hadir' ? $request->number_of_guests : 0,
                'message' => $request->message,
                'responded_at' => now(),
            ]
        );

        // Save wish message if provided
        if ($request->message) {
            WishMessage::create([
                'event_id' => $guest->event_id,
                'guest_id' => $guest->id,
                'name' => $guest->name,
                'message' => $request->message,
            ]);
        }

        ActivityLog::log('rsvp_submit', "RSVP dari {$guest->name}: {$request->status}", $rsvp);

        if ($request->status === 'hadir') {
            return redirect()->route('invitation.ticket', $slug)
                ->with('success', 'RSVP berhasil! Berikut tiket digital Anda.');
        }

        return back()->with('success', 'Terima kasih telah mengkonfirmasi. Kami menghargai kehadiran Anda.');
    }

    public function ticket(string $slug)
    {
        $guest = Guest::where('slug', $slug)->with(['event', 'rsvp'])->firstOrFail();
        $event = $guest->event;

        if (!$guest->rsvp || $guest->rsvp->status !== 'hadir') {
            return redirect()->route('invitation.show', $slug);
        }

        $qrCodeService = app(QrCodeService::class);
        $qrSvg = $qrCodeService->generateInlineSvg($guest->qr_code, 200);

        return view('invitation.ticket', compact('guest', 'event', 'qrSvg'));
    }

    public function storeWish(Request $request, string $eventSlug)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:500',
        ]);

        WishMessage::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Ucapan berhasil dikirim!');
    }
}
