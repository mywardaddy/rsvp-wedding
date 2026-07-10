<x-app-layout>
    <x-slot name="header">Tambah Paket Baru</x-slot>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('admin.pricing.store') }}" id="package-form">
            @csrf

            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-box text-amber-500 mr-2"></i>Informasi Paket</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nama Paket <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="contoh: Silver" required>
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Slug (otomatis)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" class="form-input" placeholder="otomatis dari nama">
                        @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-input" rows="2" placeholder="Deskripsi singkat paket...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price') }}" class="form-input" placeholder="999000" min="0" required>
                        @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Badge</label>
                        <input type="text" name="badge" value="{{ old('badge') }}" class="form-input" placeholder="contoh: Most Popular">
                        @error('badge') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Urutan Tampil</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-input" min="0">
                    </div>

                    <div class="flex items-center gap-6 pt-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                            <span class="text-sm text-gray-700">Aktif</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-purple-500 focus:ring-purple-400">
                            <span class="text-sm text-gray-700">Unggulan (Featured)</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Discount Section --}}
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-percentage text-green-500 mr-2"></i>Pengaturan Diskon</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tipe Diskon</label>
                        <select name="discount_type" id="discount_type" class="form-input" onchange="toggleDiscountValue()">
                            <option value="none" {{ old('discount_type') === 'none' ? 'selected' : '' }}>Tanpa Diskon</option>
                            <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                        </select>
                    </div>

                    <div id="discount_value_wrapper" style="{{ old('discount_type', 'none') === 'none' ? 'display:none' : '' }}">
                        <label class="form-label">Nilai Diskon</label>
                        <input type="number" name="discount_value" value="{{ old('discount_value', 0) }}" class="form-input" min="0" placeholder="0">
                        @error('discount_value') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Features Section --}}
            <div class="glass-card p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-list-check text-blue-500 mr-2"></i>Daftar Fitur</h3>
                    <button type="button" onclick="addFeature()" class="btn-outline text-xs py-1.5 px-3">
                        <i class="fas fa-plus"></i> Tambah Fitur
                    </button>
                </div>

                <div id="features-container" class="space-y-3">
                    {{-- Dynamic features will be added here --}}
                </div>

                <p class="text-xs text-gray-400 mt-3"><i class="fas fa-info-circle mr-1"></i>Centang untuk menandai fitur tersedia. Hapus centang untuk fitur tidak tersedia (ditampilkan dicoret).</p>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3">
                <button type="submit" class="btn-gold">
                    <i class="fas fa-save"></i> Simpan Paket
                </button>
                <a href="{{ route('admin.pricing.index') }}" class="btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        let featureIndex = 0;

        function addFeature(name = '', isIncluded = true) {
            const container = document.getElementById('features-container');
            const html = `
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/50 border border-gray-100 animate-slide-down" id="feature-${featureIndex}">
                    <label class="flex items-center cursor-pointer">
                        <input type="hidden" name="features[${featureIndex}][is_included]" value="0">
                        <input type="checkbox" name="features[${featureIndex}][is_included]" value="1" ${isIncluded ? 'checked' : ''} class="rounded border-gray-300 text-green-500 focus:ring-green-400">
                    </label>
                    <input type="text" name="features[${featureIndex}][name]" value="${name}" class="form-input flex-1" placeholder="Nama fitur..." required>
                    <button type="button" onclick="removeFeature(${featureIndex})" class="text-red-400 hover:text-red-600 p-1 transition-colors">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            featureIndex++;
        }

        function removeFeature(index) {
            document.getElementById(`feature-${index}`)?.remove();
        }

        function toggleDiscountValue() {
            const type = document.getElementById('discount_type').value;
            const wrapper = document.getElementById('discount_value_wrapper');
            wrapper.style.display = type === 'none' ? 'none' : '';
        }

        // Add some default features on page load
        const defaultFeatures = [
            'Undangan Digital', 'RSVP', 'QR Code Check-in', 'Buku Tamu Digital',
            'Galeri Foto', 'Background Music', 'Countdown', 'Google Maps'
        ];
        defaultFeatures.forEach(f => addFeature(f, true));
    </script>
    @endpush
</x-app-layout>
