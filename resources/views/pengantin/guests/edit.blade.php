<x-app-layout>
    <x-slot name="header">Edit Tamu</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="glass-card p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-user-edit text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Edit Data Tamu</h3>
                    <p class="text-xs text-gray-500">{{ $guest->name }}</p>
                </div>
            </div>

            <form action="{{ route('pengantin.guests.update', $guest) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name', $guest->name) }}" class="form-input" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $guest->phone) }}" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $guest->email) }}" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Kategori *</label>
                        <select name="category" class="form-input" required>
                            @foreach(['reguler', 'vip', 'keluarga', 'sahabat'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $guest->category) === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Grup Tamu</label>
                        <select name="guest_group_id" class="form-input">
                            <option value="">-- Pilih Grup --</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('guest_group_id', $guest->guest_group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Maks. Pendamping *</label>
                        <input type="number" name="max_companions" value="{{ old('max_companions', $guest->max_companions) }}" class="form-input" min="1" max="10" required>
                    </div>

                    <div>
                        <label class="form-label">Alamat</label>
                        <input type="text" name="address" value="{{ old('address', $guest->address) }}" class="form-input">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-input" rows="3">{{ old('notes', $guest->notes) }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('pengantin.guests.index') }}" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-gold"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
