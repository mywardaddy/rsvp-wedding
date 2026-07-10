<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Pembayaran {{ $order->order_number }} | {{ config('app.name', 'NIKAH YUK!') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .jeweled-action { background: linear-gradient(45deg, #00342b, #004d40); }
        .payment-method { transition: all 0.2s ease; }
        .payment-method:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        .payment-method.selected { border-color: #004d40; background: rgba(0, 77, 64, 0.04); box-shadow: 0 0 0 3px rgba(0, 77, 64, 0.1); }
    </style>
</head>
<body class="bg-gradient-to-br from-stone-50 via-amber-50/30 to-green-50/20 font-['Plus_Jakarta_Sans'] text-gray-800 antialiased min-h-screen">
    {{-- Nav --}}
    <nav class="bg-white/75 backdrop-blur-xl shadow-sm sticky top-0 z-50">
        <div class="flex justify-between items-center px-6 py-4 max-w-4xl mx-auto">
            <a href="{{ route('home') }}" class="text-xl font-['Noto_Serif'] font-bold text-emerald-900 tracking-tighter">NIKAH YUK!</a>
            <a href="{{ route('order.show', $order->order_number) }}" class="text-sm text-emerald-800/70 hover:text-emerald-900 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8 md:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            {{-- Payment Methods (3 cols) --}}
            <div class="lg:col-span-3">
                <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 md:p-8 shadow-xl border border-white/60">
                    <h1 class="text-2xl font-bold text-gray-800 mb-1">Pilih Metode Pembayaran</h1>
                    <p class="text-sm text-gray-500 mb-6">Pesanan <strong class="font-mono">{{ $order->order_number }}</strong></p>

                    <form method="POST" action="{{ route('order.process-payment', $order->order_number) }}" id="payment-form">
                        @csrf
                        <input type="hidden" name="payment_method" id="selected-method" value="">

                        @php
                            $categories = collect($paymentMethods)->groupBy('category');
                        @endphp

                        @foreach($categories as $category => $methods)
                        <div class="mb-6">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ $category }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($methods as $key => $method)
                                <div class="payment-method cursor-pointer border-2 border-gray-100 rounded-2xl p-4 flex items-center gap-4"
                                     data-method="{{ $key }}"
                                     onclick="selectMethod('{{ $key }}', this)">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-50 to-green-50 flex items-center justify-center text-emerald-700 flex-shrink-0">
                                        <i class="fas {{ $method['icon'] }}"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-gray-800">{{ $method['name'] }}</div>
                                        <div class="text-xs text-gray-400 truncate">{{ $method['description'] }}</div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 border-gray-200 flex items-center justify-center flex-shrink-0 method-radio">
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-600 hidden method-dot"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        @error('payment_method')
                        <p class="text-sm text-red-500 mb-4"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror

                        <button type="submit" id="pay-btn" class="w-full jeweled-action text-white px-8 py-4 rounded-2xl text-lg font-semibold shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-transform duration-200 flex items-center justify-center gap-3 opacity-50 cursor-not-allowed" disabled>
                            <i class="fas fa-lock"></i>
                            <span>Bayar {{ $order->formatted_total_amount }}</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Order Summary (2 cols) --}}
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 shadow-xl border border-white/60 sticky top-24">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Ringkasan Pesanan</h3>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">No. Pesanan</span>
                            <span class="font-mono font-medium text-gray-800 text-xs">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Paket</span>
                            <span class="font-semibold text-gray-800">{{ $order->package_name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Pengantin</span>
                            <span class="text-gray-800">{{ $order->groom_name }} & {{ $order->bride_name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Tanggal</span>
                            <span class="text-gray-800">{{ $order->wedding_date->format('d M Y') }}</span>
                        </div>
                    </div>

                    <hr class="border-gray-100 mb-4">

                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Harga</span>
                            <span class="{{ $order->discount_amount > 0 ? 'line-through text-gray-400' : 'text-gray-800' }}">{{ $order->formatted_original_price }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Diskon</span>
                            <span class="text-red-500">-{{ $order->formatted_discount_amount }}</span>
                        </div>
                        @endif
                        <hr class="border-gray-100">
                        <div class="flex justify-between items-center pt-1">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="text-xl font-bold text-emerald-700">{{ $order->formatted_total_amount }}</span>
                        </div>
                    </div>

                    {{-- Security Notice --}}
                    <div class="mt-6 p-3 rounded-xl bg-emerald-50/50 border border-emerald-100">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-shield-alt text-emerald-500 text-sm mt-0.5"></i>
                            <div class="text-xs text-emerald-700">
                                <strong>Pembayaran Aman</strong>
                                <p class="text-emerald-600 mt-0.5">Transaksi Anda dilindungi dengan enkripsi end-to-end.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedMethod = null;

        function selectMethod(method, el) {
            // Remove selection from all
            document.querySelectorAll('.payment-method').forEach(m => {
                m.classList.remove('selected');
                m.querySelector('.method-radio').classList.remove('border-emerald-600');
                m.querySelector('.method-radio').classList.add('border-gray-200');
                m.querySelector('.method-dot').classList.add('hidden');
            });

            // Select clicked
            el.classList.add('selected');
            el.querySelector('.method-radio').classList.add('border-emerald-600');
            el.querySelector('.method-radio').classList.remove('border-gray-200');
            el.querySelector('.method-dot').classList.remove('hidden');

            // Update hidden input
            document.getElementById('selected-method').value = method;
            selectedMethod = method;

            // Enable button
            const btn = document.getElementById('pay-btn');
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        document.getElementById('payment-form').addEventListener('submit', function(e) {
            if (!selectedMethod) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Metode Pembayaran',
                    text: 'Silakan pilih metode pembayaran terlebih dahulu.',
                    confirmButtonColor: '#004d40',
                });
            }
        });
    </script>
</body>
</html>
