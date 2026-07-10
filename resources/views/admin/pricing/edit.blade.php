<x-app-layout>
    <x-slot name="header">Edit Paket: {{ $package->name }}</x-slot>

    <div class="max-w-3xl">
        {{-- Edit Package Info --}}
        <form method="POST" action="{{ route('admin.pricing.update', $package) }}">
            @csrf
            @method('PUT')

            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-box text-amber-500 mr-2"></i>Informasi Paket</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nama Paket <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $package->name) }}" class="form-input" required>
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $package->slug) }}" class="form-input">
                        @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-input" rows="2">{{ old('description', $package->description) }}</textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $package->price) }}" class="form-input" min="0" required>
                        @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Badge</label>
                        <input type="text" name="badge" value="{{ old('badge', $package->badge) }}" class="form-input" placeholder="contoh: Most Popular">
                    </div>

                    <div>
                        <label class="form-label">Urutan Tampil</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order) }}" class="form-input" min="0">
                    </div>

                    <div class="flex items-center gap-6 pt-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                            <span class="text-sm text-gray-700">Aktif</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-purple-500 focus:ring-purple-400">
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
                            <option value="none" {{ old('discount_type', $package->discount_type) === 'none' ? 'selected' : '' }}>Tanpa Diskon</option>
                            <option value="percentage" {{ old('discount_type', $package->discount_type) === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed" {{ old('discount_type', $package->discount_type) === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                        </select>
                    </div>

                    <div id="discount_value_wrapper" style="{{ old('discount_type', $package->discount_type) === 'none' ? 'display:none' : '' }}">
                        <label class="form-label">Nilai Diskon</label>
                        <input type="number" name="discount_value" value="{{ old('discount_value', $package->discount_value) }}" class="form-input" min="0">
                        @error('discount_value') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($package->has_discount)
                <div class="mt-4 p-3 rounded-xl bg-green-50/80 border border-green-200/50">
                    <p class="text-sm text-green-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Harga setelah diskon: <strong>{{ $package->formatted_discounted_price }}</strong>
                        (hemat {{ $package->discount_label }})
                    </p>
                </div>
                @endif
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 mb-6">
                <button type="submit" class="btn-gold">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.pricing.index') }}" class="btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>

        {{-- Features Management --}}
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-list-check text-blue-500 mr-2"></i>Daftar Fitur</h3>
            </div>

            {{-- Add Feature Form --}}
            <form method="POST" action="{{ route('admin.pricing.features.store', $package) }}" class="flex items-end gap-3 mb-6 p-4 rounded-xl bg-blue-50/50 border border-blue-100/50">
                @csrf
                <div class="flex-1">
                    <label class="form-label text-xs">Nama Fitur Baru</label>
                    <input type="text" name="name" class="form-input" placeholder="Nama fitur..." required>
                </div>
                <label class="flex items-center gap-2 cursor-pointer pb-2.5">
                    <input type="hidden" name="is_included" value="0">
                    <input type="checkbox" name="is_included" value="1" checked class="rounded border-gray-300 text-green-500 focus:ring-green-400">
                    <span class="text-xs text-gray-600">Tersedia</span>
                </label>
                <button type="submit" class="btn-sage text-xs py-2.5">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </form>

            {{-- Existing Features --}}
            <div class="space-y-2">
                @forelse($package->features as $feature)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/50 border border-gray-100 group hover:border-amber-200/50 transition-colors">
                    <div class="flex-1 flex items-center gap-3">
                        @if($feature->is_included)
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-sm text-gray-700 font-medium">{{ $feature->name }}</span>
                        @else
                        <i class="fas fa-times-circle text-gray-300"></i>
                        <span class="text-sm text-gray-400">{{ $feature->name }}</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        {{-- Toggle Included --}}
                        <form method="POST" action="{{ route('admin.pricing.features.update', $feature) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $feature->name }}">
                            <input type="hidden" name="is_included" value="{{ $feature->is_included ? '0' : '1' }}">
                            <button type="submit" class="p-1.5 rounded-lg hover:bg-amber-50 text-gray-400 hover:text-amber-600 transition-colors" title="{{ $feature->is_included ? 'Tandai tidak tersedia' : 'Tandai tersedia' }}">
                                <i class="fas {{ $feature->is_included ? 'fa-toggle-on text-green-500' : 'fa-toggle-off' }}"></i>
                            </button>
                        </form>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.pricing.features.destroy', $feature) }}" class="inline" data-confirm="Hapus fitur ini?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600 transition-colors">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-list text-2xl mb-2"></i>
                    <p class="text-sm">Belum ada fitur. Tambahkan fitur di atas.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleDiscountValue() {
            const type = document.getElementById('discount_type').value;
            const wrapper = document.getElementById('discount_value_wrapper');
            wrapper.style.display = type === 'none' ? 'none' : '';
        }
    </script>
    @endpush
</x-app-layout>
