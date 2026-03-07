<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfigurasi Database - POS Assco</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">

    <div class="max-w-md w-full">
        <!-- Logo / Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center p-4 bg-indigo-600 rounded-3xl shadow-xl shadow-indigo-200 mb-4 transition-transform hover:scale-110 duration-500">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Konfigurasi Server</h1>
            <p class="text-slate-500 mt-2 font-medium">Tentukan parameter koneksi database Anda</p>
        </div>

        <!-- Alert Section -->
        @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-start gap-3 animate-pulse">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <p class="text-sm font-bold">{{ session('error') }}</p>
        </div>
        @endif

        @if(session('warning'))
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-2xl flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <p class="text-sm font-bold">{{ session('warning') }}</p>
        </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 p-8 sm:p-10 border border-slate-100 backdrop-blur-sm bg-white/90">
            <form action="{{ route('db.config.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label for="host" class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">IP Address / Host</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <input type="text" name="host" id="host" value="{{ old('host', $config['host'] ?? '127.0.0.1') }}" 
                               class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-100 focus:bg-white transition-all outline-none" 
                               placeholder="Contoh: 192.168.1.10" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="database" class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Database</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <input type="text" name="database" id="database" value="{{ old('database', $config['database'] ?? 'produk') }}" 
                               class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-100 focus:bg-white transition-all outline-none" 
                               placeholder="Nama DB" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="username" class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">User DB</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $config['username'] ?? 'root') }}" 
                               class="block w-full px-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-100 focus:bg-white transition-all outline-none" 
                               placeholder="User" required>
                    </div>
                    <div class="space-y-2">
                        <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" id="password" value="{{ old('password', $config['password'] ?? '') }}" 
                               class="block w-full px-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-100 focus:bg-white transition-all outline-none" 
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-3xl font-black text-lg shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        SIMPAN & TES KONEKSI
                    </button>
                    <a href="{{ route('login') }}" class="block text-center mt-6 text-slate-400 text-sm font-bold hover:text-indigo-600 transition-colors uppercase tracking-widest">Hanya Lanjutkan ke Login →</a>
                </div>
            </form>
        </div>

        <p class="text-center mt-10 text-slate-400 text-xs font-medium uppercase tracking-tighter">
            &copy; 2026 POS Assco Enterprise System
        </p>
    </div>

</body>
</html>
