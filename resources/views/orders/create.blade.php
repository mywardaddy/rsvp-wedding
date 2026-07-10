<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Pesan Paket {{ $package->name }} | {{ config('app.name', 'NIKAH YUK!') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <style>
        .glass-morphism { background: rgba(251, 249, 249, 0.65); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .jeweled-action { background: linear-gradient(45deg, #00342b, #004d40); }
    </style>
</head>
<body class="bg-gradient-to-br from-stone-50 via-amber-50/30 to-green-50/20 font-['Plus_Jakarta_Sans'] text-gray-800 antialiased min-h-screen">
    {{-- Nav --}}
    <nav class="bg-white/75 backdrop-blur-xl shadow-sm sticky top-0 z-50">
        <div class="flex justify-between items-center px-6 py-4 max-w-5xl mx-auto">
            <a href="{{ route('home') }}" class="text-xl font-['Noto_Serif'] font-bold text-emerald-900 tracking-tighter">NIKAH YUK!</a>
            <a href="{{ route('home') }}#pricing" class="text-sm text-emerald-800/70 hover:text-emerald-900 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Paket
            </a>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 py-8 md:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            {{-- Form Section (3 cols) --}}
            <div class="lg:col-span-3">
                <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 md:p-8 shadow-xl border border-white/60">
                    <h1 class="text-2xl font-bold text-gray-800 mb-1">Form Pemesanan</h1>
                    <p class="text-sm text-gray-500 mb-6">Lengkapi data berikut untuk memesan paket <strong>{{ $package->name }}</strong></p>

                    @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <ul class="list-disc list-inside mt-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('order.store') }}">
                        @csrf
                        <input type="hidden" name="pricing_package_id" value="{{ $package->id }}">

                        <div class="space-y-5">
                            {{-- Customer Info --}}
                            <div class="pb-2">
                                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4">
                                    <i class="fas fa-user text-amber-500 mr-2"></i>Data Pemesan
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                        <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Email <span class="text-red-500">*</span></label>
                                        <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="email@contoh.com" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                        <input type="text" name="customer_whatsapp" value="{{ old('customer_whatsapp', auth()->user()?->phone) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="08123456789" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Wedding Info --}}
                            <div class="pb-2">
                                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4">
                                    <i class="fas fa-heart text-red-400 mr-2"></i>Data Pernikahan
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Pengantin Pria <span class="text-red-500">*</span></label>
                                        <input type="text" name="groom_name" value="{{ old('groom_name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="Nama pengantin pria" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Pengantin Wanita <span class="text-red-500">*</span></label>
                                        <input type="text" name="bride_name" value="{{ old('bride_name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="Nama pengantin wanita" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Pernikahan <span class="text-red-500">*</span></label>
                                        <input type="date" name="wedding_date" value="{{ old('wedding_date') }}" min="{{ now()->addDay()->format('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <button type="submit" class="w-full jeweled-action text-white px-8 py-4 rounded-2xl text-lg font-semibold shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-transform duration-200 flex items-center justify-center gap-3">
                                <i class="fas fa-paper-plane"></i>
                                Buat Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Package Summary (2 cols) --}}
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 shadow-xl border border-white/60 sticky top-24">
                    <div class="text-center mb-4">
                        @if($package->badge)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-amber-400 to-amber-600 text-white mb-3">
                            <i class="fas fa-star mr-1"></i> {{ $package->badge }}
                        </span>
                        @endif
                        <h3 class="text-2xl font-bold text-gray-800">Paket {{ $package->name }}</h3>
                    </div>

                    {{-- Price --}}
                    <div class="text-center mb-6 p-4 rounded-2xl bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-100">
                        @if($package->has_discount)
                        <div class="text-sm text-gray-400 line-through mb-1">{{ $package->formatted_price }}</div>
                        <div class="inline-flex items-center gap-2 mb-1">
                            <span class="text-3xl font-bold text-emerald-700">{{ $package->formatted_discounted_price }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-600">-{{ $package->discount_label }}</span>
                        </div>
                        <div class="text-xs text-emerald-600">Hemat {{ \App\Models\PricingPackage::formatRupiah($package->discount_amount) }}</div>
                        @else
                        <div class="text-3xl font-bold text-emerald-700">{{ $package->formatted_price }}</div>
                        @endif
                    </div>

                    {{-- Features --}}
                    <div class="space-y-2 mb-6">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Fitur yang didapat:</h4>
                        @foreach($package->features as $feature)
                        <div class="flex items-center gap-2.5 text-sm">
                            @if($feature->is_included)
                            <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                            <span class="text-gray-700">{{ $feature->name }}</span>
                            @else
                            <i class="fas fa-times-circle text-gray-300 text-xs"></i>
                            <span class="text-gray-400 line-through">{{ $feature->name }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Summary --}}
                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Paket</span>
                            <span class="font-medium">{{ $package->name }}</span>
                        </div>
                        @if($package->has_discount)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Harga Asli</span>
                            <span class="text-gray-400 line-through">{{ $package->formatted_price }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Diskon</span>
                            <span class="text-red-500">-{{ \App\Models\PricingPackage::formatRupiah($package->discount_amount) }}</span>
                        </div>
                        @endif
                        <hr class="border-gray-100">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="text-xl font-bold text-emerald-700">{{ $package->has_discount ? $package->formatted_discounted_price : $package->formatted_price }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
