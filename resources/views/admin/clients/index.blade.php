<x-app-layout>
    <x-slot name="header">Kelola Client</x-slot>

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <p class="text-sm text-gray-500">Semua akun Pengantin otomatis tampil sebagai client. Tambah akun Pengantin melalui
                <a href="{{ route('admin.users.create') }}" class="text-amber-600 font-semibold hover:underline">Kelola Users</a>.
            </p>
        </div>

        {{-- Search & Filter Bar --}}
        <div class="glass-card p-4">
            <form method="GET" action="{{ route('admin.clients.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau event..."
                           class="form-input pl-10 w-full" />
                </div>
                <select name="status" class="form-input w-full sm:w-52" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="unconfigured" {{ request('status') === 'unconfigured' ? 'selected' : '' }}>Belum Dikonfigurasi</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Selesai</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn-gold">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.clients.index') }}" class="btn-outline text-center">
                    <i class="fas fa-times"></i> Reset
                </a>
                @endif
            </form>
        </div>

        {{-- Stats Summary --}}
        @php
            $totalClients = $clients->total();
            $activeCount = $clients->filter(fn($c) => $c->is_active && $c->clientEvent?->status === 'active')->count();
            $unconfiguredCount = $clients->filter(fn($c) => $c->is_active && $c->clientEvent?->status === 'unconfigured')->count();
            $inactiveCount = $clients->filter(fn($c) => !$c->is_active)->count();
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $totalClients }}</div>
                <div class="text-xs text-gray-500 mt-1">Total Client</div>
            </div>
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-green-700">{{ $activeCount }}</div>
                <div class="text-xs text-gray-500 mt-1">Aktif</div>
            </div>
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-amber-700">{{ $unconfiguredCount }}</div>
                <div class="text-xs text-gray-500 mt-1">Belum Dikonfigurasi</div>
            </div>
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-red-500">{{ $inactiveCount }}</div>
                <div class="text-xs text-gray-500 mt-1">Nonaktif</div>
            </div>
        </div>

        {{-- Client Cards Grid --}}
        @if($clients->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($clients as $client)
            @php
                $event = $client->clientEvent;
                $isConfigured = $event && $event->isConfigured();
                $isActive = $client->is_active;

                if (!$isActive) {
                    $statusClass = 'bg-red-50 text-red-700 border-red-200';
                    $statusDotClass = 'text-red-500';
                    $statusLabel = 'Nonaktif';
                } elseif (!$isConfigured) {
                    $statusClass = 'bg-orange-50 text-orange-700 border-orange-200';
                    $statusDotClass = 'text-orange-500';
                    $statusLabel = 'Belum Dikonfigurasi';
                } else {
                    $statusClass = match($event->status) {
                        'active' => 'bg-green-50 text-green-700 border-green-200',
                        'done' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'draft' => 'bg-gray-50 text-gray-600 border-gray-200',
                        default => 'bg-gray-50 text-gray-600 border-gray-200',
                    };
                    $statusDotClass = match($event->status) {
                        'active' => 'text-green-500',
                        'done' => 'text-amber-500',
                        'draft' => 'text-gray-400',
                        default => 'text-gray-400',
                    };
                    $statusLabel = $event->status_label;
                }
            @endphp
            <div class="glass-card overflow-hidden group relative {{ !$isActive ? 'opacity-60' : '' }}">
                {{-- Status Badge --}}
                <div class="absolute top-4 right-4 z-10">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                        <i class="fas fa-circle text-[6px] {{ $statusDotClass }}"></i>
                        {{ $statusLabel }}
                    </span>
                </div>

                {{-- Cover Image (only for configured events) --}}
                @if($isConfigured && $event->cover_image)
                <div class="h-32 overflow-hidden">
                    <img src="{{ Storage::url($event->cover_image) }}" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                @endif

                {{-- Card Content --}}
                <div class="p-5">
                    {{-- Account Info --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $isConfigured ? 'from-amber-400 to-amber-600' : 'from-gray-300 to-gray-400' }} flex items-center justify-center text-white shadow-md flex-shrink-0">
                            @if($isConfigured)
                                <i class="fas fa-heart text-sm"></i>
                            @else
                                <i class="fas fa-user text-sm"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            @if($isConfigured)
                                <h3 class="font-bold text-gray-800 truncate text-base">{{ $event->groom_name }} & {{ $event->bride_name }}</h3>
                                <p class="text-xs text-gray-400 truncate">{{ $event->title }}</p>
                            @else
                                <h3 class="font-bold text-gray-800 truncate text-base">{{ $client->name }}</h3>
                                <p class="text-xs text-gray-400 truncate">{{ $client->email }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="space-y-2.5 mb-5">
                        {{-- Account info (always shown) --}}
                        <div class="flex items-center gap-2.5 text-sm">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-blue-500 text-xs"></i>
                            </div>
                            <span class="text-gray-600 truncate">{{ $client->name }} · {{ $client->email }}</span>
                        </div>

                        @if($isConfigured)
                            {{-- Event details --}}
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
                                <span class="text-gray-600"><strong>{{ $event->guests_count ?? 0 }}</strong> Tamu Undangan</span>
                            </div>
                        @else
                            {{-- Unconfigured message --}}
                            <div class="p-3 rounded-lg bg-orange-50/60 border border-orange-100 text-xs text-orange-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Data event belum dilengkapi. Klik "Lengkapi Data" untuk mengisi detail pernikahan.
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2">
                        @if($isConfigured && $isActive)
                            {{-- Kelola --}}
                            <form method="POST" action="{{ route('admin.clients.manage', $event) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="btn-gold w-full justify-center group-hover:shadow-lg transition-shadow text-sm">
                                    <i class="fas fa-external-link-alt"></i> Kelola
                                </button>
                            </form>
                            {{-- Edit --}}
                            <a href="{{ route('admin.clients.edit', $client) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all duration-200" title="Edit Client">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                        @elseif(!$isConfigured && $isActive)
                            {{-- Lengkapi Data --}}
                            <a href="{{ route('admin.clients.setup', $client) }}" class="btn-gold flex-1 text-center justify-center text-sm">
                                <i class="fas fa-clipboard-list"></i> Lengkapi Data
                            </a>
                        @endif

                        @if($isActive)
                            {{-- Toggle Nonaktif --}}
                            <form method="POST" action="{{ route('admin.clients.toggle', $client) }}" class="inline" data-confirm="Nonaktifkan client {{ $client->name }}?">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 text-gray-500 hover:text-orange-600 hover:border-orange-200 hover:bg-orange-50 transition-all duration-200" title="Nonaktifkan">
                                    <i class="fas fa-ban text-sm"></i>
                                </button>
                            </form>
                        @else
                            {{-- Toggle Aktifkan --}}
                            <form method="POST" action="{{ route('admin.clients.toggle', $client) }}" class="flex-1" data-confirm="Aktifkan kembali client {{ $client->name }}?">
                                @csrf
                                <button type="submit" class="btn-outline w-full justify-center text-sm border-green-200 text-green-600 hover:bg-green-50">
                                    <i class="fas fa-check-circle"></i> Aktifkan
                                </button>
                            </form>
                        @endif

                        {{-- Hapus Event Data --}}
                        @if($isConfigured)
                        <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" class="inline" data-confirm="Hapus data event client ini? Semua data tamu akan terhapus. Akun pengantin TIDAK akan dihapus.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 text-gray-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all duration-200" title="Hapus Data Event">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                {{-- Managed Indicator --}}
                @if($event && session('managed_event_id') == $event->id)
                <div class="px-5 py-2 text-center text-xs font-semibold" style="background: linear-gradient(135deg, rgba(201,176,55,0.15), rgba(156,175,136,0.1)); color: #8B7A1E;">
                    <i class="fas fa-check-circle mr-1"></i> Sedang Dikelola
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($clients->hasPages())
        <div class="pagination-wrapper">
            {{ $clients->links() }}
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
                    Tidak ditemukan client dengan filter yang dipilih.
                @else
                    Belum ada akun Pengantin yang terdaftar. Buat akun Pengantin baru melalui menu Kelola Users, dan client akan otomatis muncul di sini.
                @endif
            </p>
            <div class="flex items-center justify-center gap-3 mt-4">
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.clients.index') }}" class="btn-outline inline-flex">
                    <i class="fas fa-times"></i> Reset Filter
                </a>
                @endif
                <a href="{{ route('admin.users.create') }}" class="btn-gold inline-flex">
                    <i class="fas fa-user-plus"></i> Tambah Akun Pengantin
                </a>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
