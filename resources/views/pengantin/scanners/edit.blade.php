<x-app-layout>
    <x-slot name="header">Edit Petugas Scanner</x-slot>

    <div class="max-w-2xl">
        {{-- Event Context --}}
        <div class="glass-card p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                    <i class="fas fa-heart text-white text-xs"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-700">{{ $event->groom_name }} & {{ $event->bride_name }}</p>
                    <p class="text-xs text-gray-400">{{ $event->title }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="glass-card p-6">
            <form method="POST" action="{{ route('pengantin.scanners.update', $scanner) }}">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="form-label">Nama Petugas <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $scanner->user->name) }}" class="form-input" required>
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="form-label">Email Login <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $scanner->user->email) }}" class="form-input" required>
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="form-label">No. Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $scanner->user->phone) }}" class="form-input">
                        @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Status Info --}}
                <div class="mt-5 p-3 rounded-xl {{ $scanner->is_active ? 'bg-green-50' : 'bg-gray-50' }}">
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fas fa-circle text-[8px] {{ $scanner->is_active ? 'text-green-500' : 'text-gray-400' }}"></i>
                        <span class="{{ $scanner->is_active ? 'text-green-700' : 'text-gray-500' }} font-medium">
                            Status: {{ $scanner->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                    <button type="submit" class="btn-gold">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('pengantin.scanners.index') }}" class="btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
