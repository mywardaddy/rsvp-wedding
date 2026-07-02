<x-app-layout>
    <x-slot name="header">Tambah Petugas Scanner</x-slot>

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
            <form method="POST" action="{{ route('pengantin.scanners.store') }}">
                @csrf

                <div class="space-y-5">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="form-label">Nama Petugas <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input" placeholder="Nama lengkap petugas" required>
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="form-label">Email Login <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input" placeholder="email@contoh.com" required>
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="form-label">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" class="form-input" placeholder="Minimal 8 karakter" required>
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="form-label">No. Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-input" placeholder="08xxxxxxxxxx">
                        @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                    <button type="submit" class="btn-gold">
                        <i class="fas fa-save"></i>
                        Simpan Petugas
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
