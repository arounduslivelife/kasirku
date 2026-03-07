@extends('layout')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center p-4 bg-indigo-600 rounded-3xl shadow-xl shadow-indigo-200 mb-4 transition-transform hover:scale-110 duration-500">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Buka Shift Kasir</h1>
        <p class="text-slate-500 mt-2 font-medium">Masukkan saldo awal laci kasir (Modal Tunai)</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 p-8 border border-slate-100">
        <form action="{{ route('shift.open') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="saldo_awal" class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Saldo Awal (Tunai)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <span class="font-bold">Rp</span>
                        </div>
                        <input type="number" name="saldo_awal" id="saldo_awal" min="0" class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-100 focus:bg-white transition-all outline-none" placeholder="0" required autofocus>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-3xl font-black text-lg shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                    MULAI SHIFT SEKARANG
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
