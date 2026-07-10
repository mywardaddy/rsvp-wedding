<x-app-layout>
    <x-slot name="header">Petugas Scanner</x-slot>

    <div class="space-y-6">
        {{-- Event Info & Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-700">{{ $event->groom_name }} & {{ $event->bride_name }}</h3>
                <p class="text-sm text-gray-400">{{ $event->title }} · {{ $event->date->format('d M Y') }}</p>
            </div>
            <a href="{{ route('pengantin.scanners.create') }}" class="btn-gold">
                <i class="fas fa-plus"></i>
                Tambah Petugas
            </a>
        </div>

        {{-- Scanner Cards --}}
        @if($scanners->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($scanners as $scanner)
            <div class="glass-card overflow-hidden">
                <div class="p-5">
                    {{-- User Info --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $scanner->is_active ? 'from-green-400 to-green-600' : 'from-gray-400 to-gray-500' }} flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($scanner->user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-800 truncate">{{ $scanner->user->name }}</h4>
                                <span class="badge {{ $scanner->is_active ? 'badge-sage' : 'badge-belum' }} flex-shrink-0">
                                    {{ $scanner->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-400 truncate">{{ $scanner->user->email }}</p>
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="space-y-1.5 mb-4">
                        @if($scanner->user->phone)
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i class="fas fa-phone text-xs w-4 text-center"></i>
                            <span>{{ $scanner->user->phone }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i class="fas fa-clock text-xs w-4 text-center"></i>
                            <span>Bergabung {{ $scanner->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('pengantin.scanners.edit', $scanner) }}" class="btn-outline text-xs px-3 py-1.5">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <form method="POST" action="{{ route('pengantin.scanners.toggle-active', $scanner) }}">
                            @csrf
                            <button type="submit" class="btn-outline text-xs px-3 py-1.5 {{ $scanner->is_active ? '' : 'text-green-600' }}" style="{{ !$scanner->is_active ? 'border-color:rgba(76,175,80,0.3);color:#2E7D32;' : '' }}">
                                <i class="fas {{ $scanner->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                {{ $scanner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('pengantin.scanners.reset-password', $scanner) }}" data-confirm="Reset password ke 'password'?">
                            @csrf
                            <button type="submit" class="btn-outline text-xs px-3 py-1.5">
                                <i class="fas fa-key"></i> Reset Password
                            </button>
                        </form>

                        <form method="POST" action="{{ route('pengantin.scanners.destroy', $scanner) }}" data-confirm="Hapus petugas ini dari event?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-semibold border-2 transition-all duration-300" style="border-color:rgba(229,115,115,0.3);color:#C62828;">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        {{-- Empty State --}}
        <div class="glass-card p-12 text-center">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-qrcode text-green-500 text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Petugas Scanner</h3>
            <p class="text-sm text-gray-400 max-w-md mx-auto mb-4">
                Tambahkan petugas scanner agar mereka dapat melakukan check-in tamu pada hari acara.
            </p>
            <a href="{{ route('pengantin.scanners.create') }}" class="btn-gold inline-flex">
                <i class="fas fa-plus"></i>
                Tambah Petugas
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
