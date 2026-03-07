<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Gudang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased h-screen overflow-hidden flex" x-data="{ sidebarOpen: false }">
    
    <!-- Sidebar Overlay (Mobile) -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden" 
         x-cloak></div>

    <!-- Sidebar (Desktop & Mobile) -->
    <div class="fixed inset-y-0 left-0 bg-indigo-700 text-white w-64 flex flex-col shadow-lg z-50 transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
         @keydown.escape.window="sidebarOpen = false">
        
        @auth
        <div class="h-16 flex items-center justify-between px-6 bg-indigo-800 border-b border-indigo-600/50">
            <a href="{{ route('dashboard') }}" class="font-bold text-xl tracking-tight flex items-center gap-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                POS Assco
            </a>
            <!-- Close button for Mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden text-indigo-200 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto py-6">
            <nav class="px-4 space-y-3">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-white text-indigo-700 shadow-md' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-indigo-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                @if(auth()->user()->hasPermission('manage-pos') || auth()->user()->hasPermission('view-pos-history'))
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Layanan Kasir</p>
                </div>
                @endif

                @php
                    $activeShift = \App\Models\Shift::where('user_id', auth()->id())->where('status', 'open')->first();
                @endphp

                @if(auth()->user()->hasPermission('manage-pos'))
                <a href="{{ route('pos.index') }}" class="{{ request()->routeIs('pos.index') ? 'bg-white text-indigo-700 shadow-md' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('pos.index') ? 'text-indigo-600' : 'text-indigo-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Buka Kasir (POS)
                </a>

                @if($activeShift)
                <a href="{{ route('shift.close') }}" class="mt-1 bg-rose-600 hover:bg-rose-700 block px-4 py-3 rounded-xl transition-all duration-200 font-bold flex items-center gap-3 text-white border border-rose-400/30">
                    <svg class="w-5 h-5 text-rose-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3 3L22 4m-2 6V4m0 0H9m11 0l-7 7m-4 8H3a2 2 0 01-2-2v-5a2 2 0 012-2h11"></path></svg>
                    Tutup Kasir
                </a>
                @endif
                @endif

                @if(auth()->user()->hasPermission('view-pos-history'))
                <a href="{{ route('pos.history') }}" class="{{ request()->routeIs('pos.history') || request()->routeIs('pos.receipt') ? 'bg-indigo-800 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3 mt-1 text-sm">
                    <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Riwayat Penjualan
                </a>
                <a href="{{ route('piutang.index') }}" class="{{ request()->routeIs('piutang.*') ? 'bg-indigo-800 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3 mt-1 text-sm">
                    <svg class="w-5 h-5 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Daftar Piutang (Bon)
                </a>
                @endif
                @if(auth()->user()->hasPermission('view-produk') || auth()->user()->hasPermission('view-pelanggan') || auth()->user()->hasPermission('view-pemasok'))
                <div class="pb-2">
                    <p class="px-4 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Master Data</p>
                </div>
                @endif
                
                @if(auth()->user()->hasPermission('view-produk'))
                <a href="{{ route('produk.index') }}" class="{{ request()->routeIs('produk.*') ? 'bg-indigo-800 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('produk.*') ? 'text-indigo-200' : 'text-indigo-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    Master Produk
                </a>
                @endif

                @if(auth()->user()->hasPermission('view-pelanggan'))
                <a href="{{ route('pelanggan.index') }}" class="{{ request()->routeIs('pelanggan.*') ? 'bg-indigo-800 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3 mt-1">
                    <svg class="w-5 h-5 {{ request()->routeIs('pelanggan.*') ? 'text-indigo-200' : 'text-indigo-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Pelanggan
                </a>
                @endif

                @if(auth()->user()->hasPermission('view-pemasok'))
                <a href="{{ route('pemasok.index') }}" class="{{ request()->routeIs('pemasok.*') ? 'bg-indigo-800 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3 mt-1">
                    <svg class="w-5 h-5 {{ request()->routeIs('pemasok.*') ? 'text-indigo-200' : 'text-indigo-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Pemasok
                </a>
                @endif
                
                @if(auth()->user()->hasPermission('view-invin') || auth()->user()->hasPermission('view-invout'))
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Transaksi Stok</p>
                </div>
                @endif
                
                @if(auth()->user()->hasPermission('view-invin'))
                <a href="{{ route('invin.index') }}" class="{{ request()->routeIs('invin.*') ? 'bg-emerald-600 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('invin.*') ? 'text-emerald-200' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                    Stok Masuk
                </a>
                @endif

                @if(auth()->user()->hasPermission('view-invout'))
                <a href="{{ route('invout.index') }}" class="{{ request()->routeIs('invout.*') ? 'bg-rose-500 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3 mt-1">
                    <svg class="w-5 h-5 {{ request()->routeIs('invout.*') ? 'text-rose-200' : 'text-rose-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path></svg>
                    Stok Keluar
                </a>
                @endif
 
                @if(auth()->user()->hasPermission('manage-users') || auth()->user()->hasPermission('manage-settings') || auth()->user()->hasPermission('view-activity-log') || auth()->user()->hasPermission('manage-permissions'))
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Laporan & Pengaturan</p>
                </div>
                @endif

                @if(auth()->user()->hasPermission('manage-users'))
                <a href="{{ route('user.index') }}" class="{{ request()->routeIs('user.*') ? 'bg-indigo-800 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('user.*') ? 'text-indigo-200' : 'text-indigo-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Manajemen Pengguna
                </a>
                @endif

                @if(auth()->user()->hasPermission('manage-settings'))
                <a href="{{ route('pengeluaran.index') }}" class="{{ request()->routeIs('pengeluaran.*') ? 'bg-rose-500 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3 mt-1 text-sm">
                    <svg class="w-5 h-5 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Biaya & Pengeluaran
                </a>
                @endif

                @if(auth()->user()->hasPermission('manage-permissions'))
                <a href="{{ route('permission.index') }}" class="{{ request()->routeIs('permission.*') ? 'bg-indigo-800 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3 mt-1">
                    <svg class="w-5 h-5 {{ request()->routeIs('permission.*') ? 'text-indigo-200' : 'text-indigo-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Pengaturan Otoritas
                </a>
                @endif

                @if(auth()->user()->hasPermission('manage-settings'))
                <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'bg-indigo-800 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3 mt-1">
                    <svg class="w-5 h-5 {{ request()->routeIs('settings.*') ? 'text-indigo-200' : 'text-indigo-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan HPP
                </a>
                @endif

                @if(auth()->user()->hasPermission('view-activity-log'))
                <a href="{{ route('activity_log.index') }}" class="{{ request()->routeIs('activity_log.*') ? 'bg-indigo-800 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3 mt-1">
                    <svg class="w-5 h-5 {{ request()->routeIs('activity_log.*') ? 'text-indigo-200' : 'text-indigo-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Log Aktivitas
                </a>
                @endif
                @if(auth()->user()->hasPermission('view-pos-history'))
                <a href="{{ route('report.index') }}" class="{{ request()->routeIs('report.*') ? 'bg-indigo-800 shadow-inner' : 'hover:bg-indigo-600' }} block px-4 py-3 rounded-xl transition-all duration-200 font-medium flex items-center gap-3 mt-1 text-sm border-t border-indigo-600/30 pt-3">
                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan Penjualan (Excel)
                </a>
                @endif
            </nav>
        </div>
        @endauth
    </div>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
        <!-- Top header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-8 z-10 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <!-- Hamburger button for Mobile -->
                @auth
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-indigo-600 transition-colors p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                @endauth
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 truncate">
                    @if(request()->routeIs('dashboard')) Ringkasan Bisnis @endif
                    @if(request()->routeIs('pos.index')) Terminal Kasir @endif
                    @if(request()->routeIs('pos.history') || request()->routeIs('pos.receipt')) Riwayat Penjualan @endif
                    @if(request()->routeIs('produk.*')) Database Produk @endif
                    @if(request()->routeIs('pelanggan.*')) Database Pelanggan @endif
                    @if(request()->routeIs('pemasok.*')) Database Pemasok @endif
                    @if(request()->routeIs('invin.*')) Riwayat Stok Masuk @endif
                    @if(request()->routeIs('invout.*')) Riwayat Stok Keluar @endif
                    @if(request()->routeIs('user.*')) Manajemen Pengguna @endif
                    @if(request()->routeIs('activity_log.*')) Log Aktivitas App @endif
                    @if(request()->routeIs('settings.*')) Pengaturan Aplikasi @endif
                    @if(request()->routeIs('permission.*')) Pengaturan Otoritas @endif
                    @if(request()->routeIs('pengeluaran.*')) Biaya & Pengeluaran @endif
                </h2>
            </div>
            <div class="flex items-center gap-2 sm:gap-4 text-sm font-medium">
                @auth
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-gray-900 font-semibold">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-indigo-600 uppercase tracking-wide px-2 py-0.5 bg-indigo-50 rounded-full">{{ auth()->user()->role }}</span>
                    </div>
                    <div class="hidden sm:block h-8 w-px bg-gray-200 mx-2"></div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-rose-600 transition-colors flex items-center gap-1.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="hidden xs:inline">Logout</span>
                        </button>
                    </form>
                @endauth
            </div>
        </header>
        
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-8">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 sm:px-5 sm:py-4 rounded-xl relative mb-8 shadow-sm flex items-center gap-3" role="alert">
                        <svg class="w-6 h-6 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="block sm:inline font-medium text-sm sm:text-base">{{ session('success') }}</span>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
