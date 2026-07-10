<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Wedding Guest Management') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-stone-50 via-amber-50/30 to-green-50/20">

            <!-- Sidebar -->
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
                <div class="h-full flex flex-col glass-sidebar">
                    <!-- Logo -->
                    <div class="flex items-center gap-3 px-6 py-5 border-b border-white/20">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-rings-wedding text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-sm font-bold text-gray-800 leading-tight">Wedding</h1>
                            <p class="text-xs text-gray-500">Guest Management</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                        @if(auth()->user()->isPengantin() || (auth()->user()->isSuperadmin() && isset($managedEvent)))
                        <div class="mb-4">
                            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
                            <a href="{{ route('pengantin.dashboard') }}" class="nav-link {{ request()->routeIs('pengantin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-th-large w-5"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('pengantin.guests.index') }}" class="nav-link {{ request()->routeIs('pengantin.guests.*') ? 'active' : '' }}">
                                <i class="fas fa-users w-5"></i>
                                <span>Daftar Tamu</span>
                            </a>
                            <a href="{{ route('pengantin.groups.index') }}" class="nav-link {{ request()->routeIs('pengantin.groups.*') ? 'active' : '' }}">
                                <i class="fas fa-layer-group w-5"></i>
                                <span>Grup Tamu</span>
                            </a>
                            <a href="{{ route('pengantin.scanners.index') }}" class="nav-link {{ request()->routeIs('pengantin.scanners.*') ? 'active' : '' }}">
                                <i class="fas fa-qrcode w-5"></i>
                                <span>Petugas Scanner</span>
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->isSuperadmin())
                        <div class="mb-4">
                            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Admin</p>
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-chart-pie w-5"></i>
                                <span>Admin Dashboard</span>
                            </a>
                            <a href="{{ route('admin.clients.index') }}" class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                                <i class="fas fa-heart w-5"></i>
                                <span>Kelola Client</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fas fa-user-shield w-5"></i>
                                <span>Kelola Users</span>
                            </a>
                            <a href="{{ route('admin.pricing.index') }}" class="nav-link {{ request()->routeIs('admin.pricing.*') ? 'active' : '' }}">
                                <i class="fas fa-tags w-5"></i>
                                <span>Kelola Paket</span>
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart w-5"></i>
                                <span>Kelola Pesanan</span>
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->isPetugasScan())
                        <div class="mb-4">
                            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Scanner</p>
                            <a href="{{ route('scanner.index') }}" class="nav-link {{ request()->routeIs('scanner.*') ? 'active' : '' }}">
                                <i class="fas fa-qrcode w-5"></i>
                                <span>Scan QR Code</span>
                            </a>
                        </div>
                        @endif
                    </nav>

                    <!-- User Info -->
                    <div class="p-4 border-t border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-green-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-700 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ auth()->user()->role->display_name }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Overlay for mobile sidebar -->
            <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

            <!-- Main Content -->
            <div class="lg:ml-64 min-h-screen">
                <!-- Top Bar -->
                <header class="sticky top-0 z-30 glass-topbar">
                    <div class="flex items-center justify-between px-4 sm:px-6 py-3">
                        <div class="flex items-center gap-4">
                            <button onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-gray-800">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            @isset($header)
                                <h2 class="text-lg font-semibold text-gray-800">{{ $header }}</h2>
                            @endisset
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400">{{ now()->format('l, d M Y') }}</span>
                        </div>
                    </div>

                    {{-- Active Client Indicator for Superadmin --}}
                    @if(auth()->user()->isSuperadmin() && isset($managedEvent) && $managedEvent)
                    <div class="flex items-center justify-between px-4 sm:px-6 py-2 border-t border-amber-200/50" style="background: linear-gradient(135deg, rgba(201,176,55,0.08), rgba(156,175,136,0.06));">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                                <i class="fas fa-heart text-white text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold leading-none">Mengelola</p>
                                <p class="text-sm font-bold text-gray-700 leading-tight">Wedding {{ $managedEvent->groom_name }} & {{ $managedEvent->bride_name }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.clients.switch-back') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 border" style="border-color: rgba(201,176,55,0.3); color: #8B7A1E; background: rgba(255,255,255,0.6);" onmouseover="this.style.background='rgba(201,176,55,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.6)'">
                                <i class="fas fa-exchange-alt"></i>
                                Ganti Client
                            </button>
                        </form>
                    </div>
                    @endif
                </header>

                <!-- Page Content -->
                <main class="p-4 sm:p-6">
                    @if(session('success'))
                    <script>document.addEventListener('DOMContentLoaded', function(){ Swal.fire({ icon: 'success', title: 'Berhasil!', text: {!! json_encode(session('success')) !!}, toast: true, position: 'top-end', timer: 4000, showConfirmButton: false, timerProgressBar: true }); });</script>
                    @endif

                    @if(session('error'))
                    <script>document.addEventListener('DOMContentLoaded', function(){ Swal.fire({ icon: 'error', title: 'Gagal!', text: {!! json_encode(session('error')) !!}, toast: true, position: 'top-end', timer: 4000, showConfirmButton: false, timerProgressBar: true }); });</script>
                    @endif

                    @if(session('info'))
                    <script>document.addEventListener('DOMContentLoaded', function(){ Swal.fire({ icon: 'info', title: 'Informasi', text: {!! json_encode(session('info')) !!}, toast: true, position: 'top-end', timer: 4000, showConfirmButton: false, timerProgressBar: true }); });</script>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        </script>

        @stack('scripts')
    </body>
</html>
