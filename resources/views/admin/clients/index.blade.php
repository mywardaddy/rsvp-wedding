<x-app-layout>
    <x-slot name="header">Kelola Client</x-slot>

    <div class="space-y-6">
        {{-- Search & Filter Bar --}}
        <div class="glass-card p-4">
            <form method="GET" action="{{ route('admin.clients.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengantin, pasangan, atau event..."
                           class="form-input pl-10 w-full" />
                </div>
                <select name="status" class="form-input w-full sm:w-40" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn-gold">
                    <i class="fas fa-search"></i>
                    Cari
                </button>
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.clients.index') }}" class="btn-outline text-center">
                    <i class="fas fa-times"></i>
                    Reset
                </a>
                @endif
            </form>
        </div>

        {{-- Stats Summary --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $events->total() }}</div>
                <div class="text-xs text-gray-500 mt-1">Total Client</div>
            </div>
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-green-700">{{ $events->where('is_active', true)->count() }}</div>
                <div class="text-xs text-gray-500 mt-1">Event Aktif</div>
            </div>
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-amber-700">{{ $events->sum('guests_count') }}</div>
                <div class="text-xs text-gray-500 mt-1">Total Tamu</div>
            </div>
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-purple-700">{{ $events->where('is_active', false)->count() }}</div>
                <div class="text-xs text-gray-500 mt-1">Event Nonaktif</div>
            </div>
        </div>

        {{-- Client Cards Grid --}}
        @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($events as $event)
            <div class="glass-card overflow-hidden group relative">
                {{-- Status Indicator --}}
                <div class="absolute top-4 right-4">
                    <span class="badge {{ $event->is_active ? 'badge-sage' : 'badge-belum' }}">
                        <i class="fas fa-circle text-[6px] mr-1.5 {{ $event->is_active ? 'text-green-500' : 'text-gray-400' }}"></i>
                        {{ $event->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                {{-- Card Content --}}
                <div class="p-5">
                    {{-- Couple Names --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white shadow-md flex-shrink-0">
                            <i class="fas fa-heart text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-gray-800 truncate text-base">{{ $event->groom_name }} & {{ $event->bride_name }}</h3>
                            <p class="text-xs text-gray-400 truncate">{{ $event->title }}</p>
                        </div>
                    </div>

                    {{-- Event Details --}}
                    <div class="space-y-2.5 mb-5">
                        <div class="flex items-center gap-2.5 text-sm">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar-alt text-amber-500 text-xs"></i>
                            </div>
                            <span class="text-gray-600">{{ $event->date->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-sm">
                            <div class="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-green-500 text-xs"></i>
                            </div>
                            <span class="text-gray-600 truncate">{{ $event->venue_name }}</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-sm">
                            <div class="w-7 h-7 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-users text-purple-500 text-xs"></i>
                            </div>
                            <span class="text-gray-600"><strong>{{ $event->guests_count }}</strong> Tamu Undangan</span>
                        </div>
                        @if($event->user)
                        <div class="flex items-center gap-2.5 text-sm">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-blue-500 text-xs"></i>
                            </div>
                            <span class="text-gray-600 truncate">{{ $event->user->name }} · {{ $event->user->email }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Action Button --}}
                    <form method="POST" action="{{ route('admin.clients.manage', $event) }}">
                        @csrf
                        <button type="submit" class="btn-gold w-full justify-center group-hover:shadow-lg transition-shadow">
                            <i class="fas fa-external-link-alt"></i>
                            Kelola Client
                        </button>
                    </form>
                </div>

                {{-- Managed Indicator --}}
                @if(session('managed_event_id') == $event->id)
                <div class="px-5 py-2 text-center text-xs font-semibold" style="background: linear-gradient(135deg, rgba(201,176,55,0.15), rgba(156,175,136,0.1)); color: #8B7A1E;">
                    <i class="fas fa-check-circle mr-1"></i> Sedang Dikelola
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($events->hasPages())
        <div class="pagination-wrapper">
            {{ $events->links() }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="glass-card p-12 text-center">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-heart text-amber-500 text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Client</h3>
            <p class="text-sm text-gray-400 max-w-md mx-auto">
                @if(request()->hasAny(['search', 'status']))
                    Tidak ditemukan client dengan filter yang dipilih. Coba ubah kata kunci pencarian.
                @else
                    Belum ada akun pengantin yang terdaftar. Buat akun pengantin baru melalui menu Kelola Users.
                @endif
            </p>
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.clients.index') }}" class="btn-outline mt-4 inline-flex">
                <i class="fas fa-times"></i>
                Reset Filter
            </a>
            @endif
        </div>
        @endif
    </div>
</x-app-layout>
