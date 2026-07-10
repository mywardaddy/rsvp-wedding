<x-app-layout>
    <x-slot name="header">Kelola Pesanan</x-slot>

    <div class="space-y-6">
        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-500">Total Pesanan</div>
            </div>
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-gray-500">Menunggu Bayar</div>
            </div>
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['paid'] }}</div>
                <div class="text-xs text-gray-500">Lunas</div>
            </div>
            <div class="glass-card p-4 text-center">
                <div class="text-2xl font-bold text-red-500">{{ $stats['cancelled'] }}</div>
                <div class="text-xs text-gray-500">Dibatalkan</div>
            </div>
        </div>

        {{-- Filter & Search --}}
        <div class="glass-card p-4">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Cari nama, email, no. pesanan...">
                </div>
                <select name="status" class="form-input sm:w-48">
                    <option value="">Semua Status</option>
                    <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Menunggu Bayar</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                </select>
                <button type="submit" class="btn-gold">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>
        </div>

        {{-- Orders Table --}}
        <div class="glass-card-static overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Total</th>
                            <th>Tanggal Nikah</th>
                            <th>Status</th>
                            <th>Tanggal Order</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-amber-700 hover:text-amber-900">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div class="text-sm font-medium text-gray-800">{{ $order->customer_name }}</div>
                                <div class="text-xs text-gray-400">{{ $order->customer_email }}</div>
                            </td>
                            <td><span class="badge badge-gold">{{ $order->package_name }}</span></td>
                            <td class="font-semibold text-gray-800">{{ $order->formatted_total_amount }}</td>
                            <td class="text-sm text-gray-600">{{ $order->wedding_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ match($order->status) {
                                    'pending_payment' => 'bg-amber-100 text-amber-700',
                                    'paid' => 'badge-sage',
                                    'cancelled' => 'badge-tidak-hadir',
                                    'expired' => 'badge-belum',
                                    default => 'badge-belum'
                                } }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="text-xs text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-amber-600 hover:text-amber-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-400">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>Belum ada pesanan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="pagination-wrapper">
            {{ $orders->withQueryString()->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
