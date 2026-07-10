<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Pesanan {{ $order->order_number }} | {{ config('app.name', 'NIKAH YUK!') }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        .jeweled-action { background: linear-gradient(45deg, #00342b, #004d40); }
    </style>
</head>
<body class="bg-gradient-to-br from-stone-50 via-amber-50/30 to-green-50/20 font-['Plus_Jakarta_Sans'] text-gray-800 antialiased min-h-screen">
    {{-- Nav --}}
    <nav class="bg-white/75 backdrop-blur-xl shadow-sm sticky top-0 z-50">
        <div class="flex justify-between items-center px-6 py-4 max-w-4xl mx-auto">
            <a href="{{ route('home') }}" class="text-xl font-['Noto_Serif'] font-bold text-emerald-900 tracking-tighter">NIKAH YUK!</a>
            <a href="{{ route('home') }}" class="text-sm text-emerald-800/70 hover:text-emerald-900 transition-colors">
                <i class="fas fa-home mr-1"></i> Beranda
            </a>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8 md:py-12">
        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('info'))
        <div class="mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 flex items-center gap-3">
            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            <span>{{ session('info') }}</span>
        </div>
        @endif

        {{-- Status Header --}}
        <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 md:p-8 shadow-xl border border-white/60 mb-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center {{ match($order->status) {
                        'pending_payment' => 'bg-amber-100 text-amber-600',
                        'paid' => 'bg-green-100 text-green-600',
                        'cancelled' => 'bg-red-100 text-red-500',
                        'expired' => 'bg-gray-100 text-gray-500',
                        default => 'bg-gray-100 text-gray-500'
                    } }}">
                        <i class="fas {{ match($order->status) {
                            'pending_payment' => 'fa-clock',
                            'paid' => 'fa-check-circle',
                            'cancelled' => 'fa-times-circle',
                            'expired' => 'fa-hourglass-end',
                            default => 'fa-question'
                        } }} text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Status Pesanan</div>
                        <div class="text-xl font-bold {{ match($order->status) {
                            'pending_payment' => 'text-amber-600',
                            'paid' => 'text-green-600',
                            'cancelled' => 'text-red-500',
                            'expired' => 'text-gray-500',
                            default => 'text-gray-500'
                        } }}">{{ $order->status_label }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400">No. Pesanan</div>
                    <div class="text-lg font-bold text-gray-800 font-mono">{{ $order->order_number }}</div>
                </div>
            </div>

            @if($order->isPendingPayment())
            <a href="{{ route('order.payment', $order->order_number) }}" class="w-full jeweled-action text-white px-8 py-4 rounded-2xl text-lg font-semibold shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-transform duration-200 flex items-center justify-center gap-3">
                <i class="fas fa-credit-card"></i> Bayar Sekarang
            </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Customer Info --}}
            <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 shadow-lg border border-white/60">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">
                    <i class="fas fa-user text-amber-500 mr-2"></i>Data Pemesan
                </h3>
                <div class="space-y-3">
                    <div><span class="text-xs text-gray-400">Nama</span><p class="font-medium">{{ $order->customer_name }}</p></div>
                    <div><span class="text-xs text-gray-400">Email</span><p class="font-medium">{{ $order->customer_email }}</p></div>
                    <div><span class="text-xs text-gray-400">WhatsApp</span><p class="font-medium">{{ $order->customer_whatsapp }}</p></div>
                </div>
            </div>

            {{-- Wedding Info --}}
            <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 shadow-lg border border-white/60">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">
                    <i class="fas fa-heart text-red-400 mr-2"></i>Data Pernikahan
                </h3>
                <div class="space-y-3">
                    <div><span class="text-xs text-gray-400">Pengantin Pria</span><p class="font-medium">{{ $order->groom_name }}</p></div>
                    <div><span class="text-xs text-gray-400">Pengantin Wanita</span><p class="font-medium">{{ $order->bride_name }}</p></div>
                    <div><span class="text-xs text-gray-400">Tanggal Pernikahan</span><p class="font-medium">{{ $order->wedding_date->format('d F Y') }}</p></div>
                </div>
            </div>
        </div>

        {{-- Payment Summary --}}
        <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 md:p-8 shadow-xl border border-white/60">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">
                <i class="fas fa-receipt text-green-500 mr-2"></i>Ringkasan Pembayaran
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Paket</span>
                    <span class="font-semibold text-gray-800">{{ $order->package_name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Harga</span>
                    <span class="{{ $order->discount_amount > 0 ? 'line-through text-gray-400' : 'text-gray-800' }}">
                        {{ $order->formatted_original_price }}
                    </span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Diskon {{ $order->discount_type === 'percentage' ? '(' . $order->discount_value . '%)' : '' }}</span>
                    <span class="text-red-500 font-medium">-{{ $order->formatted_discount_amount }}</span>
                </div>
                @endif
                <hr class="border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-800">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-emerald-700">{{ $order->formatted_total_amount }}</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
