<x-app-layout>
    <x-slot name="header">Grup Tamu</x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Add Group -->
        <div class="glass-card p-5 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-plus-circle text-amber-500 mr-2"></i>Tambah Grup Baru</h3>
            <form action="{{ route('pengantin.groups.store') }}" method="POST" class="flex flex-wrap gap-3 items-end">
                @csrf
                <div class="flex-1 min-w-[200px]">
                    <label class="form-label">Nama Grup *</label>
                    <input type="text" name="name" class="form-input" required placeholder="Contoh: Keluarga Pria">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="form-label">Deskripsi</label>
                    <input type="text" name="description" class="form-input" placeholder="Opsional">
                </div>
                <div class="w-20">
                    <label class="form-label">Warna</label>
                    <input type="color" name="color" value="#C9B037" class="form-input p-1 h-[42px]">
                </div>
                <button type="submit" class="btn-gold h-[42px]"><i class="fas fa-plus"></i> Tambah</button>
            </form>
        </div>

        <!-- Groups List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($groups as $group)
            <div class="glass-card p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-full shadow-sm" style="background: {{ $group->color }}"></div>
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $group->name }}</h4>
                            @if($group->description)
                            <p class="text-xs text-gray-400">{{ $group->description }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="badge badge-gold">{{ $group->guests_count }} tamu</span>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <a href="{{ route('pengantin.guests.index', ['group' => $group->id]) }}" class="text-xs text-amber-600 hover:text-amber-700 font-medium">
                        <i class="fas fa-eye mr-1"></i>Lihat Tamu
                    </a>
                    <span class="text-gray-300">·</span>
                    <form action="{{ route('pengantin.groups.destroy', $group) }}" method="POST" class="inline" data-confirm="Hapus grup {{ $group->name }}?">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-400 hover:text-red-600 font-medium"><i class="fas fa-trash mr-1"></i>Hapus</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="glass-card p-8 text-center sm:col-span-2">
                <i class="fas fa-layer-group text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm">Belum ada grup tamu</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
