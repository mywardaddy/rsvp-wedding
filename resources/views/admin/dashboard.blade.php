<x-app-layout>
    <x-slot name="header">Admin Dashboard</x-slot>

    <div class="bento-grid">
        <!-- Stats Row -->
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-gradient-to-br from-amber-400 to-amber-600"><i class="fas fa-users"></i></div>
                <span class="text-xs text-amber-600 font-medium bg-amber-50 px-2 py-1 rounded-lg">Users</span>
            </div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-gradient-to-br from-green-400 to-green-600"><i class="fas fa-calendar-alt"></i></div>
                <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-lg">Events</span>
            </div>
            <div class="stat-value text-green-700">{{ $totalEvents }}</div>
            <div class="stat-label">Total Events</div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-gradient-to-br from-purple-400 to-purple-600"><i class="fas fa-user-friends"></i></div>
                <span class="text-xs text-purple-600 font-medium bg-purple-50 px-2 py-1 rounded-lg">Guests</span>
            </div>
            <div class="stat-value text-purple-700">{{ $totalGuests }}</div>
            <div class="stat-label">Total Tamu</div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-gradient-to-br from-blue-400 to-blue-600"><i class="fas fa-qrcode"></i></div>
                <span class="text-xs text-blue-600 font-medium bg-blue-50 px-2 py-1 rounded-lg">Scan</span>
            </div>
            <div class="stat-value text-blue-700">{{ $totalCheckins }}</div>
            <div class="stat-label">Total Check-in</div>
        </div>

        <!-- RSVP Overview -->
        <div class="glass-card p-5 bento-span-2">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-envelope-open-text text-amber-500 mr-2"></i>RSVP Overview</h3>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="text-center p-3 rounded-xl bg-green-50/80">
                    <div class="text-2xl font-bold text-green-700">{{ $rsvpHadir }}</div>
                    <div class="text-xs text-green-600">Hadir</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-red-50/80">
                    <div class="text-2xl font-bold text-red-600">{{ $rsvpTidakHadir }}</div>
                    <div class="text-xs text-red-500">Tidak Hadir</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-blue-50/80">
                    <div class="text-2xl font-bold text-blue-600">{{ $totalRsvps }}</div>
                    <div class="text-xs text-blue-500">Total RSVP</div>
                </div>
            </div>
        </div>

        <!-- Users by Role -->
        <div class="glass-card p-5 bento-span-2">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-user-shield text-purple-500 mr-2"></i>Users per Role</h3>
            <div class="space-y-3">
                @foreach($usersByRole as $role => $count)
                <div class="flex items-center justify-between p-3 rounded-xl bg-white/50">
                    <span class="text-sm font-medium text-gray-700">{{ $role }}</span>
                    <span class="badge badge-gold">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Events -->
        <div class="glass-card p-5 bento-span-2">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-calendar text-green-500 mr-2"></i>Events Terbaru</h3>
            <div class="space-y-3">
                @foreach($events as $event)
                <div class="flex items-center justify-between p-3 rounded-xl bg-white/50 hover:bg-white/80 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $event->title }}</p>
                        <p class="text-xs text-gray-400">{{ $event->date->format('d M Y') }} · {{ $event->guests_count }} tamu</p>
                    </div>
                    <span class="badge {{ $event->is_active ? 'badge-sage' : 'badge-belum' }}">
                        {{ $event->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="glass-card p-5 bento-span-2">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-history text-orange-500 mr-2"></i>Aktivitas Terbaru</h3>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @foreach($recentActivity as $log)
                <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-white/50 transition-colors">
                    <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-circle text-amber-400 text-[6px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-700">{{ $log->description }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">
                            {{ $log->user?->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}
                            @if($log->ip_address) · {{ $log->ip_address }} @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
