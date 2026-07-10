<x-app-layout>
    <x-slot name="header">Kelola Users</x-slot>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">Kelola semua pengguna sistem</p>
        <a href="{{ route('admin.users.create') }}" class="btn-gold"><i class="fas fa-plus"></i> Tambah User</a>
    </div>

    <!-- Filters -->
    <div class="glass-card-static p-4 mb-6">
        <form class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="form-input flex-1 min-w-[200px]">
            <select name="role" class="form-input w-auto">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-gold"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="glass-card-static overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th class="hidden sm:table-cell">Telepon</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="group">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-green-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-gold">{{ $user->role->display_name }}</span></td>
                        <td class="hidden sm:table-cell text-sm">{{ $user->phone ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $user->is_active ? 'badge-hadir' : 'badge-tidak-hadir' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 rounded-lg hover:bg-blue-50 text-gray-500 hover:text-blue-600 transition-colors"><i class="fas fa-edit text-sm"></i></a>
                                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="inline" data-confirm="Reset password {{ $user->name }}?">
                                    @csrf
                                    <button class="p-2 rounded-lg hover:bg-orange-50 text-gray-500 hover:text-orange-600 transition-colors"><i class="fas fa-key text-sm"></i></button>
                                </form>
                                <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="p-2 rounded-lg hover:bg-purple-50 text-gray-500 hover:text-purple-600 transition-colors">
                                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} text-sm"></i>
                                    </button>
                                </form>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" data-confirm="Hapus {{ $user->name }}?">
                                    @csrf @method('DELETE')
                                    <button class="p-2 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 transition-colors"><i class="fas fa-trash text-sm"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-8 text-gray-400">Tidak ada user</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-4 border-t border-gray-100/50 pagination-wrapper">{{ $users->links() }}</div>
        @endif
    </div>
</x-app-layout>
