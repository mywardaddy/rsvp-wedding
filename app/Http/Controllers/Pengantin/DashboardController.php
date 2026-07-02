<?php

namespace App\Http\Controllers\Pengantin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ResolvesClientEvent;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Checkin;
use App\Models\Rsvp;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ResolvesClientEvent;

    public function index(Request $request)
    {
        $user = auth()->user();
        $event = $this->resolveEvent();

        if (!$event) {
            return redirect()->route('pengantin.events.create')
                ->with('info', 'Silakan buat acara pernikahan Anda terlebih dahulu.');
        }

        // Stats
        $totalGuests = $event->guests()->count();
        $rsvpHadir = $event->guests()->whereHas('rsvp', fn($q) => $q->where('status', 'hadir'))->count();
        $rsvpTidakHadir = $event->guests()->whereHas('rsvp', fn($q) => $q->where('status', 'tidak_hadir'))->count();
        $belumRsvp = $totalGuests - $rsvpHadir - $rsvpTidakHadir;
        $sudahCheckin = $event->guests()->whereHas('checkins')->count();
        $belumCheckin = $rsvpHadir - $sudahCheckin;
        $attendancePercent = $totalGuests > 0 ? round(($sudahCheckin / $totalGuests) * 100) : 0;

        // Chart data
        $rsvpChartData = [
            'labels' => ['Hadir', 'Tidak Hadir', 'Belum RSVP'],
            'data' => [$rsvpHadir, $rsvpTidakHadir, $belumRsvp],
            'colors' => ['#9CAF88', '#E57373', '#E5E5E5'],
        ];

        $categoryData = $event->guests()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        $categoryChartData = [
            'labels' => $categoryData->keys()->map(fn($k) => ucfirst($k))->values()->toArray(),
            'data' => $categoryData->values()->toArray(),
            'colors' => ['#C9B037', '#9CAF88', '#8B7355', '#6B8E9B'],
        ];

        // Recent check-ins
        $recentCheckins = Checkin::whereHas('guest', fn($q) => $q->where('event_id', $event->id))
            ->with(['guest', 'scanner'])
            ->latest('checked_in_at')
            ->take(5)
            ->get();

        // Recent activity
        $recentActivity = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Guest groups stats
        $groupStats = $event->guestGroups()
            ->withCount('guests')
            ->get();

        return view('pengantin.dashboard', compact(
            'event', 'totalGuests', 'rsvpHadir', 'rsvpTidakHadir', 'belumRsvp',
            'sudahCheckin', 'belumCheckin', 'attendancePercent',
            'rsvpChartData', 'categoryChartData',
            'recentCheckins', 'recentActivity', 'groupStats'
        ));
    }
}
