<x-app-layout>
    <x-slot name="header">Detail Pesanan: {{ $order->order_number }}</x-slot>

    <div class="max-w-4xl space-y-6">
        {{-- Status Banner --}}
        <div class="glass-card p-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ match($order->status) {
                    'pending_payment' => 'bg-amber-100 text-amber-600',
                    'paid' => 'bg-green-100 text-green-600',
                    'cancelled' => 'bg-red-100 text-red-600',
                    'expired' => 'bg-gray-100 text-gray-500',
                    default => 'bg-gray-100 text-gray-500'
                } }}">
                    <i class="fas {{ match($order->status) {
                        'pending_payment' => 'fa-clock',
                        'paid' => 'fa-check-circle',
                        'cancelled' => 'fa-times-circle',
                        'expired' => 'fa-hourglass-end',
                        default => 'fa-question'
                    } }} text-xl"></i>
                </div>
                <div>
                    <div class="text-lg font-bold text-gray-800">{{ $order->status_label }}</div>
                    <div class="text-xs text-gray-500">{{ $order->order_number }} · {{ $order->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>

            @if($order->isPendingPayment())
            <div class="flex gap-2">
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" data-confirm="Tandai pesanan ini sebagai LUNAS?">
                    @csrf
                    <input type="hidden" name="status" value="paid">
                    <button type="submit" class="btn-sage text-xs">
                        <i class="fas fa-check"></i> Tandai Lunas
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" data-confirm="Batalkan pesanan ini?">
                    @csrf
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn-danger text-xs">
                        <i class="fas fa-times"></i> Batalkan
                    </button>
                </form>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Customer Info --}}
            <div class="glass-card p-5">
                <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-user text-amber-500 mr-2"></i>Informasi Pelanggan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Nama</span>
                        <span class="text-sm font-medium text-gray-800">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Email</span>
                        <span class="text-sm font-medium text-gray-800">{{ $order->customer_email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">WhatsApp</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_whatsapp) }}" target="_blank" class="text-sm font-medium text-green-600 hover:text-green-800">
                            {{ $order->customer_whatsapp }} <i class="fab fa-whatsapp ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Wedding Info --}}
            <div class="glass-card p-5">
                <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-heart text-red-400 mr-2"></i>Informasi Pernikahan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Pengantin Pria</span>
                        <span class="text-sm font-medium text-gray-800">{{ $order->groom_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Pengantin Wanita</span>
                        <span class="text-sm font-medium text-gray-800">{{ $order->bride_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Tanggal</span>
                        <span class="text-sm font-medium text-gray-800">{{ $order->wedding_date->format('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Details --}}
        <div class="glass-card p-5">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-receipt text-green-500 mr-2"></i>Detail Pembayaran</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Paket</span>
                    <span class="text-sm font-medium badge badge-gold">{{ $order->package_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Harga Asli</span>
                    <span class="text-sm text-gray-800">{{ $order->formatted_original_price }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Diskon ({{ $order->discount_type === 'percentage' ? $order->discount_value . '%' : 'Potongan' }})</span>
                    <span class="text-sm text-red-500">-{{ $order->formatted_discount_amount }}</span>
                </div>
                @endif
                <hr class="border-gray-100">
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-800">Total Pembayaran</span>
                    <span class="text-lg font-bold text-green-700">{{ $order->formatted_total_amount }}</span>
                </div>
                @if($order->paid_at)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Dibayar Pada</span>
                    <span class="text-sm text-green-600">{{ $order->paid_at->format('d F Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Payment History --}}
        @if($order->payments->count() > 0)
        <div class="glass-card p-5">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-history text-blue-500 mr-2"></i>Riwayat Pembayaran</h3>
            <div class="space-y-3">
                @foreach($order->payments as $payment)
                <div class="p-3 rounded-xl bg-white/50 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-800">{{ $payment->payment_method ?? 'Manual' }}</span>
                        <span class="badge {{ $payment->isSuccess() ? 'badge-sage' : ($payment->isPending() ? 'bg-amber-100 text-amber-700' : 'badge-tidak-hadir') }}">
                            {{ $payment->status_label }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $payment->formatted_amount }} · {{ $payment->created_at->format('d M Y H:i') }}
                        @if($payment->payment_gateway) · {{ strtoupper($payment->payment_gateway) }} @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Back --}}
        <a href="{{ route('admin.orders.index') }}" class="btn-outline inline-flex">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
    </div>
</x-app-layout>
