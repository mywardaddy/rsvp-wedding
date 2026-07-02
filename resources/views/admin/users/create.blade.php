<x-app-layout>
    <x-slot name="header">{{ isset($user) ? 'Edit User' : 'Tambah User' }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="glass-card p-6 sm:p-8">
            <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" class="space-y-5">
                @csrf
                @if(isset($user)) @method('PUT') @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="form-label">Nama *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-input" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-input" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if(!isset($user))
                    <div>
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <div>
                        <label class="form-label">Role *</label>
                        <select name="role_id" class="form-input" required>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-input">
                    </div>

                    @if(isset($user))
                    <div>
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-input">
                            <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.users.index') }}" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-gold"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
