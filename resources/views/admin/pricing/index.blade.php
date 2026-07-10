<x-app-layout>
    <x-slot name="header">Kelola Paket Harga</x-slot>

    <div class="space-y-6">
        {{-- Header Actions --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Pricing Packages</h3>
                <p class="text-sm text-gray-500">Kelola paket harga yang ditampilkan di halaman utama</p>
            </div>
            <a href="{{ route('admin.pricing.create') }}" class="btn-gold">
                <i class="fas fa-plus"></i> Tambah Paket
            </a>
        </div>

        {{-- Package Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="packages-grid">
            @forelse($packages as $package)
            <div class="glass-card p-6 relative {{ !$package->is_active ? 'opacity-60' : '' }}" data-id="{{ $package->id }}">
                {{-- Status Badge --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        @if($package->badge)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-amber-400 to-amber-600 text-white">
                            <i class="fas fa-star mr-1 text-[10px]"></i> {{ $package->badge }}
                        </span>
                        @endif
                        @if($package->is_featured)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-100 text-purple-700">
                            <i class="fas fa-crown mr-1"></i> Featured
                        </span>
                        @endif
                    </div>
                    <span class="badge {{ $package->is_active ? 'badge-sage' : 'badge-belum' }}">
                        {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                {{-- Package Name & Price --}}
                <h4 class="text-xl font-bold text-gray-800 mb-1">{{ $package->name }}</h4>
                @if($package->description)
                <p class="text-xs text-gray-500 mb-3">{{ Str::limit($package->description, 80) }}</p>
                @endif

                <div class="mb-4">
                    @if($package->has_discount)
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm text-gray-400 line-through">{{ $package->formatted_price }}</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600">
                            -{{ $package->discount_label }}
                        </span>
                    </div>
                    <div class="text-2xl font-bold text-green-700">{{ $package->formatted_discounted_price }}</div>
                    @else
                    <div class="text-2xl font-bold text-gray-800">{{ $package->formatted_price }}</div>
                    @endif
                </div>

                {{-- Features Preview --}}
                <div class="space-y-1.5 mb-6 max-h-48 overflow-y-auto">
                    @foreach($package->features as $feature)
                    <div class="flex items-center gap-2 text-sm">
                        @if($feature->is_included)
                        <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        <span class="text-gray-700">{{ $feature->name }}</span>
                        @else
                        <i class="fas fa-times-circle text-gray-300 text-xs"></i>
                        <span class="text-gray-400">{{ $feature->name }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.pricing.edit', $package) }}" class="btn-outline flex-1 justify-center text-xs py-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.pricing.toggle-active', $package) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full btn-outline justify-center text-xs py-2 {{ $package->is_active ? 'text-red-600 border-red-200' : 'text-green-600 border-green-200' }}">
                            <i class="fas {{ $package->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            {{ $package->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    @if(!$package->orders()->exists())
                    <form method="POST" action="{{ route('admin.pricing.destroy', $package) }}" data-confirm="Hapus paket {{ $package->name }}?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                    @endif
                </div>

                {{-- Sort Order Indicator --}}
                <div class="absolute top-3 right-3 w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-400" title="Urutan: {{ $package->sort_order }}">
                    {{ $package->sort_order }}
                </div>
            </div>
            @empty
            <div class="glass-card p-12 text-center col-span-full">
                <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tags text-amber-400 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Paket</h4>
                <p class="text-sm text-gray-500 mb-4">Buat paket harga pertama untuk ditampilkan di halaman utama</p>
                <a href="{{ route('admin.pricing.create') }}" class="btn-gold">
                    <i class="fas fa-plus"></i> Buat Paket Pertama
                </a>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
