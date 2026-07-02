<x-app-layout>
    <x-slot name="header">Tambah Tamu</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="glass-card p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                    <i class="fas fa-user-plus text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Tambah Tamu Baru</h3>
                    <p class="text-xs text-gray-500">Isi data tamu undangan</p>
                </div>
            </div>

            <form action="{{ route('pengantin.guests.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="08xxxxxxxxxx">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Kategori *</label>
                        <select name="category" class="form-input" required>
                            <option value="reguler" {{ old('category') === 'reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="vip" {{ old('category') === 'vip' ? 'selected' : '' }}>VIP</option>
                            <option value="keluarga" {{ old('category') === 'keluarga' ? 'selected' : '' }}>Keluarga</option>
                            <option value="sahabat" {{ old('category') === 'sahabat' ? 'selected' : '' }}>Sahabat</option>
                        </select>
                        @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Grup Tamu</label>
                        <select name="guest_group_id" class="form-input">
                            <option value="">-- Pilih Grup --</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('guest_group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Maks. Pendamping *</label>
                        <input type="number" name="max_companions" value="{{ old('max_companions', 1) }}" class="form-input" min="1" max="10" required>
                        @error('max_companions') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Alamat</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="form-input">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-input" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('pengantin.guests.index') }}" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-gold"><i class="fas fa-save"></i> Simpan Tamu</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
