<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guest;
use App\Models\User;
use App\Models\Checkin;
use App\Models\Rsvp;
use App\Models\ActivityLog;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalGuests = Guest::count();
        $totalCheckins = Checkin::count();
        $totalRsvps = Rsvp::count();
        $activeEvents = Event::where('is_active', true)->count();

        $rsvpHadir = Rsvp::where('status', 'hadir')->count();
        $rsvpTidakHadir = Rsvp::where('status', 'tidak_hadir')->count();

        // Recent activity
        $recentActivity = ActivityLog::with('user')->latest()->take(15)->get();

        // User by role
        $usersByRole = User::selectRaw('role_id, COUNT(*) as count')
            ->groupBy('role_id')
            ->with('role')
            ->get()
            ->mapWithKeys(fn($item) => [$item->role->display_name => $item->count]);

        // Events list
        $events = Event::with('user')
            ->withCount('guests')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalEvents', 'totalGuests', 'totalCheckins',
            'totalRsvps', 'activeEvents', 'rsvpHadir', 'rsvpTidakHadir',
            'recentActivity', 'usersByRole', 'events'
        ));
    }
}
