<x-app-layout>
    <x-slot name="header">Lengkapi Data Client</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="glass-card p-6 sm:p-8">
            {{-- Header --}}
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white shadow-md">
                    <i class="fas fa-clipboard-list text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Lengkapi Data Event</h3>
                    <p class="text-xs text-gray-400">Isi detail pernikahan untuk akun pengantin ini</p>
                </div>
            </div>

            {{-- Linked Account Info (Read-only) --}}
            <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-700">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                    <div class="ml-auto">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border bg-blue-50 text-blue-700 border-blue-200">
                            <i class="fas fa-user mr-1"></i> Akun Pengantin
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.clients.store-setup', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Nama Pengantin Pria --}}
                    <div>
                        <label class="form-label">Nama Pengantin Pria *</label>
                        <input type="text" name="groom_name" value="{{ old('groom_name', $event->groom_name) }}" class="form-input" placeholder="Nama lengkap pengantin pria" required>
                        @error('groom_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Pengantin Wanita --}}
                    <div>
                        <label class="form-label">Nama Pengantin Wanita *</label>
                        <input type="text" name="bride_name" value="{{ old('bride_name', $event->bride_name) }}" class="form-input" placeholder="Nama lengkap pengantin wanita" required>
                        @error('bride_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Event --}}
                    <div class="sm:col-span-2">
                        <label class="form-label">Nama Event *</label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}" class="form-input" placeholder="Contoh: Pernikahan Ahmad & Siti" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal Pernikahan --}}
                    <div>
                        <label class="form-label">Tanggal Pernikahan *</label>
                        <input type="date" name="date" value="{{ old('date', $event->date?->format('Y-m-d')) }}" class="form-input" required>
                        @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status Event --}}
                    <div>
                        <label class="form-label">Status Event *</label>
                        <select name="status" class="form-input" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="done" {{ old('status') === 'done' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Waktu Mulai --}}
                    <div>
                        <label class="form-label">Waktu Mulai *</label>
                        <input type="time" name="time_start" value="{{ old('time_start', $event->time_start ?? '08:00') }}" class="form-input" required>
                        @error('time_start') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Waktu Selesai --}}
                    <div>
                        <label class="form-label">Waktu Selesai</label>
                        <input type="time" name="time_end" value="{{ old('time_end', $event->time_end) }}" class="form-input">
                        @error('time_end') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lokasi --}}
                    <div class="sm:col-span-2">
                        <label class="form-label">Nama Tempat / Lokasi Acara *</label>
                        <input type="text" name="venue_name" value="{{ old('venue_name', $event->venue_name) }}" class="form-input" placeholder="Contoh: Hotel Grand Ballroom" required>
                        @error('venue_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="sm:col-span-2">
                        <label class="form-label">Alamat Lengkap *</label>
                        <textarea name="venue_address" class="form-input" rows="2" placeholder="Alamat lengkap lokasi acara" required>{{ old('venue_address', $event->venue_address) }}</textarea>
                        @error('venue_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Google Maps Coordinates --}}
                    <div>
                        <label class="form-label">Latitude (Google Maps)</label>
                        <input type="text" name="venue_lat" value="{{ old('venue_lat', $event->venue_lat) }}" class="form-input" placeholder="Contoh: -6.2088">
                        @error('venue_lat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Longitude (Google Maps)</label>
                        <input type="text" name="venue_lng" value="{{ old('venue_lng', $event->venue_lng) }}" class="form-input" placeholder="Contoh: 106.8456">
                        @error('venue_lng') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Dapatkan koordinat dari <a href="https://maps.google.com" target="_blank" class="text-amber-600 underline">Google Maps</a> dengan klik kanan pada lokasi.
                        </p>
                    </div>

                    {{-- Cover Image --}}
                    <div class="sm:col-span-2">
                        <label class="form-label">Cover Undangan (Opsional)</label>
                        <div class="relative">
                            <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="form-input file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100" id="cover-input">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WebP. Maks 2MB.</p>
                        @error('cover_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        <div id="cover-preview" class="mt-3 hidden">
                            <img id="cover-preview-img" src="" alt="Preview" class="w-full max-h-48 object-cover rounded-xl border border-gray-200">
                        </div>
                    </div>

                    {{-- Theme Color --}}
                    <div class="sm:col-span-2">
                        <label class="form-label">Tema Warna</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="theme_color" value="{{ old('theme_color', $event->theme_color ?? '#C9B037') }}" class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1" id="theme-color-input">
                            <input type="text" value="{{ old('theme_color', $event->theme_color ?? '#C9B037') }}" class="form-input flex-1 font-mono text-sm" id="theme-color-text" readonly>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Pilih warna tema untuk undangan digital.</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.clients.index') }}" class="btn-outline">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn-gold">
                        <i class="fas fa-save"></i> Simpan & Aktifkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Cover image preview
        document.getElementById('cover-input')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('cover-preview');
            const previewImg = document.getElementById('cover-preview-img');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });

        // Sync color picker with text input
        document.getElementById('theme-color-input')?.addEventListener('input', function(e) {
            document.getElementById('theme-color-text').value = e.target.value;
        });
    </script>
    @endpush
</x-app-layout>
