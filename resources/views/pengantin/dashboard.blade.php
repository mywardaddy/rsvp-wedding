<x-app-layout>
    <x-slot name="header">Dashboard Pengantin</x-slot>

    <!-- Bento Grid Dashboard -->
    <div class="bento-grid">
        <!-- Stat: Total Tamu -->
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-gradient-to-br from-amber-400 to-amber-600">
                    <i class="fas fa-users"></i>
                </div>
                <span class="text-xs text-amber-600 font-medium bg-amber-50 px-2 py-1 rounded-lg">Total</span>
            </div>
            <div class="stat-value">{{ $totalGuests }}</div>
            <div class="stat-label">Total Tamu Undangan</div>
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-amber-400/10 rounded-full"></div>
        </div>

        <!-- Stat: Sudah Hadir -->
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-gradient-to-br from-green-400 to-green-600">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-lg">Check-in</span>
            </div>
            <div class="stat-value text-green-700">{{ $sudahCheckin }}</div>
            <div class="stat-label">Sudah Hadir</div>
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-green-400/10 rounded-full"></div>
        </div>

        <!-- Stat: Belum Hadir -->
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-gradient-to-br from-orange-400 to-orange-600">
                    <i class="fas fa-clock"></i>
                </div>
                <span class="text-xs text-orange-600 font-medium bg-orange-50 px-2 py-1 rounded-lg">Pending</span>
            </div>
            <div class="stat-value text-orange-600">{{ $belumCheckin }}</div>
            <div class="stat-label">Belum Hadir</div>
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-orange-400/10 rounded-full"></div>
        </div>

        <!-- Stat: Persentase Kehadiran -->
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-gradient-to-br from-purple-400 to-purple-600">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <span class="text-xs text-purple-600 font-medium bg-purple-50 px-2 py-1 rounded-lg">Rate</span>
            </div>
            <div class="stat-value text-purple-700">{{ $attendancePercent }}%</div>
            <div class="stat-label">Persentase Kehadiran</div>
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-purple-400/10 rounded-full"></div>
        </div>

        <!-- RSVP Stats -->
        <div class="glass-card p-5 bento-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800"><i class="fas fa-envelope-open-text text-amber-500 mr-2"></i>Status RSVP</h3>
            </div>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="text-center p-3 rounded-xl bg-green-50/80">
                    <div class="text-2xl font-bold text-green-700">{{ $rsvpHadir }}</div>
                    <div class="text-xs text-green-600 mt-1">Hadir</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-red-50/80">
                    <div class="text-2xl font-bold text-red-600">{{ $rsvpTidakHadir }}</div>
                    <div class="text-xs text-red-500 mt-1">Tidak Hadir</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-gray-50/80">
                    <div class="text-2xl font-bold text-gray-600">{{ $belumRsvp }}</div>
                    <div class="text-xs text-gray-500 mt-1">Belum RSVP</div>
                </div>
            </div>
            <canvas id="rsvpChart" height="140"></canvas>
        </div>

        <!-- Quick Actions -->
        <div class="glass-card p-5 bento-span-2">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-bolt text-amber-500 mr-2"></i>Aksi Cepat</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <a href="{{ route('pengantin.guests.create') }}" class="quick-action">
                    <div class="action-icon bg-amber-100 text-amber-600"><i class="fas fa-user-plus"></i></div>
                    <span class="text-xs font-medium text-gray-600">Tambah Tamu</span>
                </a>
                <form action="{{ route('pengantin.guests.bulk-generate-qr') }}" method="POST" class="contents">
                    @csrf
                    <button type="submit" class="quick-action w-full">
                        <div class="action-icon bg-purple-100 text-purple-600"><i class="fas fa-qrcode"></i></div>
                        <span class="text-xs font-medium text-gray-600">Generate QR</span>
                    </button>
                </form>
                <a href="{{ route('pengantin.guests.index') }}" class="quick-action">
                    <div class="action-icon bg-blue-100 text-blue-600"><i class="fas fa-list"></i></div>
                    <span class="text-xs font-medium text-gray-600">Lihat Tamu</span>
                </a>
                <a href="{{ route('pengantin.groups.index') }}" class="quick-action">
                    <div class="action-icon bg-green-100 text-green-600"><i class="fas fa-layer-group"></i></div>
                    <span class="text-xs font-medium text-gray-600">Grup Tamu</span>
                </a>
                <a href="{{ route('invitation.show', $event->guests()->first()?->slug ?? '#') }}" class="quick-action" target="_blank">
                    <div class="action-icon bg-pink-100 text-pink-600"><i class="fas fa-share-alt"></i></div>
                    <span class="text-xs font-medium text-gray-600">Lihat Undangan</span>
                </a>
                <a href="{{ route('pengantin.guests.index', ['rsvp_status' => 'belum']) }}" class="quick-action">
                    <div class="action-icon bg-orange-100 text-orange-600"><i class="fas fa-envelope"></i></div>
                    <span class="text-xs font-medium text-gray-600">Belum RSVP</span>
                </a>
            </div>
        </div>

        <!-- Category Chart -->
        <div class="glass-card p-5 bento-span-2">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-chart-bar text-amber-500 mr-2"></i>Tamu per Kategori</h3>
            <canvas id="categoryChart" height="180"></canvas>
        </div>

        <!-- Recent Check-ins -->
        <div class="glass-card p-5 bento-span-2">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-history text-green-500 mr-2"></i>Check-in Terbaru</h3>
            <div class="space-y-3">
                @forelse($recentCheckins as $checkin)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/50 hover:bg-white/80 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($checkin->guest->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ $checkin->guest->name }}</p>
                        <p class="text-xs text-gray-400">{{ $checkin->checked_in_at->format('H:i') }} · {{ $checkin->method }}</p>
                    </div>
                    <span class="badge badge-hadir text-xs">✓ Hadir</span>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p class="text-sm">Belum ada check-in</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Guest Groups -->
        <div class="glass-card p-5 bento-span-2">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-layer-group text-amber-500 mr-2"></i>Grup Tamu</h3>
            <div class="space-y-3">
                @foreach($groupStats as $group)
                <div class="flex items-center justify-between p-3 rounded-xl bg-white/50">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full" style="background: {{ $group->color }}"></div>
                        <span class="text-sm font-medium text-gray-700">{{ $group->name }}</span>
                    </div>
                    <span class="badge badge-gold">{{ $group->guests_count }} tamu</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // RSVP Doughnut Chart
        const rsvpCtx = document.getElementById('rsvpChart').getContext('2d');
        new Chart(rsvpCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($rsvpChartData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($rsvpChartData['data']) !!},
                    backgroundColor: {!! json_encode($rsvpChartData['colors']) !!},
                    borderWidth: 0,
                    borderRadius: 4,
                    spacing: 2,
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, usePointStyle: true, pointStyleWidth: 8, font: { size: 11, family: 'Plus Jakarta Sans' } }
                    }
                }
            }
        });

        // Category Bar Chart
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($categoryChartData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($categoryChartData['data']) !!},
                    backgroundColor: {!! json_encode($categoryChartData['colors']) !!},
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: { font: { size: 11, family: 'Plus Jakarta Sans' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, family: 'Plus Jakarta Sans' } }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
