<x-app-layout>
    <x-slot name="header">Daftar Tamu</x-slot>

    <!-- Top Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-500">Kelola seluruh tamu undangan acara Anda</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('pengantin.guests.create') }}" class="btn-gold">
                <i class="fas fa-plus"></i> Tambah Tamu
            </a>
            <form action="{{ route('pengantin.guests.bulk-generate-qr') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn-sage"><i class="fas fa-qrcode"></i> Generate Semua QR</button>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card-static p-4 mb-6">
        <form action="{{ route('pengantin.guests.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, email..." class="form-input">
            </div>
            <div>
                <select name="category" class="form-input">
                    <option value="">Semua Kategori</option>
                    <option value="vip" {{ request('category') === 'vip' ? 'selected' : '' }}>VIP</option>
                    <option value="reguler" {{ request('category') === 'reguler' ? 'selected' : '' }}>Reguler</option>
                    <option value="keluarga" {{ request('category') === 'keluarga' ? 'selected' : '' }}>Keluarga</option>
                    <option value="sahabat" {{ request('category') === 'sahabat' ? 'selected' : '' }}>Sahabat</option>
                </select>
            </div>
            <div>
                <select name="group" class="form-input">
                    <option value="">Semua Grup</option>
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="rsvp_status" class="form-input">
                    <option value="">Status RSVP</option>
                    <option value="hadir" {{ request('rsvp_status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="tidak_hadir" {{ request('rsvp_status') === 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                    <option value="belum" {{ request('rsvp_status') === 'belum' ? 'selected' : '' }}>Belum RSVP</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-gold flex-1"><i class="fas fa-search"></i> Filter</button>
                <a href="{{ route('pengantin.guests.index') }}" class="btn-outline"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>

    <!-- Guest Table -->
    <div class="glass-card-static overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th class="hidden sm:table-cell">Kategori</th>
                        <th class="hidden md:table-cell">Grup</th>
                        <th>RSVP</th>
                        <th class="hidden lg:table-cell">Check-in</th>
                        <th class="hidden lg:table-cell">QR</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guests as $guest)
                    <tr class="group">
                        <td>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $guest->name }}</p>
                                <p class="text-xs text-gray-400">{{ $guest->phone ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="hidden sm:table-cell">
                            <span class="badge {{ $guest->category === 'vip' ? 'badge-vip' : 'badge-gold' }}">
                                {{ $guest->category_label }}
                            </span>
                        </td>
                        <td class="hidden md:table-cell">
                            @if($guest->guestGroup)
                            <span class="text-xs font-medium" style="color: {{ $guest->guestGroup->color }}">
                                <i class="fas fa-circle text-[6px] mr-1"></i>{{ $guest->guestGroup->name }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td>
                            @if($guest->rsvp)
                                @if($guest->rsvp->status === 'hadir')
                                <span class="badge badge-hadir"><i class="fas fa-check mr-1"></i>Hadir ({{ $guest->rsvp->number_of_guests }})</span>
                                @else
                                <span class="badge badge-tidak-hadir"><i class="fas fa-times mr-1"></i>Tidak Hadir</span>
                                @endif
                            @else
                            <span class="badge badge-belum">Belum RSVP</span>
                            @endif
                        </td>
                        <td class="hidden lg:table-cell">
                            @if($guest->is_checked_in)
                            <span class="text-green-600 text-xs font-medium"><i class="fas fa-check-circle mr-1"></i>Sudah</span>
                            @else
                            <span class="text-gray-400 text-xs">Belum</span>
                            @endif
                        </td>
                        <td class="hidden lg:table-cell">
                            @if($guest->qr_code)
                            <span class="text-xs font-mono text-gray-500">{{ $guest->qr_code }}</span>
                            @else
                            <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1 opacity-80 group-hover:opacity-100">
                                <a href="{{ route('pengantin.guests.show', $guest) }}" class="p-2 rounded-lg hover:bg-amber-50 text-gray-500 hover:text-amber-600 transition-colors" title="Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('pengantin.guests.edit', $guest) }}" class="p-2 rounded-lg hover:bg-blue-50 text-gray-500 hover:text-blue-600 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <a href="{{ route('invitation.show', $guest->slug) }}" target="_blank" class="p-2 rounded-lg hover:bg-green-50 text-gray-500 hover:text-green-600 transition-colors" title="Undangan">
                                    <i class="fas fa-external-link-alt text-sm"></i>
                                </a>
                                <form action="{{ route('pengantin.guests.destroy', $guest) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus tamu {{ $guest->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 transition-colors" title="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <div class="text-gray-400">
                                <i class="fas fa-users text-4xl mb-3"></i>
                                <p class="text-sm">Belum ada tamu undangan</p>
                                <a href="{{ route('pengantin.guests.create') }}" class="btn-gold mt-4 inline-flex">
                                    <i class="fas fa-plus"></i> Tambah Tamu Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($guests->hasPages())
        <div class="p-4 border-t border-gray-100/50 pagination-wrapper">
            {{ $guests->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
