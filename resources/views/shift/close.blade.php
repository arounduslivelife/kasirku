@extends('layout')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center p-4 bg-rose-600 rounded-3xl shadow-xl shadow-rose-200 mb-4 transition-transform hover:scale-110 duration-500">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3 3L22 4m-2 6V4m0 0H9m11 0l-7 7m-4 8H3a2 2 0 01-2-2v-5a2 2 0 012-2h11"></path></svg>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tutup Shift Kasir</h1>
        <p class="text-slate-500 mt-2 font-medium">Hitung uang tunai fisik di laci saat ini</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-rose-100/50 p-8 border border-slate-100">
        <div class="mb-8 p-4 bg-slate-50 rounded-2xl space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium tracking-tight">Saldo Awal</span>
                <span class="text-slate-900 font-bold tracking-tight">Rp {{ number_format($activeShift->saldo_awal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium tracking-tight">Total Tunai (Net)</span>
                <span class="text-slate-900 font-bold tracking-tight">Rp {{ number_format($tunaiDiharapkan - $activeShift->saldo_awal, 0, ',', '.') }}</span>
            </div>
            <div class="pt-3 border-t border-slate-200 flex justify-between">
                <span class="text-indigo-600 font-black tracking-tight">Uang Diharapkan</span>
                <span class="text-indigo-600 font-black tracking-tight text-lg">Rp {{ number_format($tunaiDiharapkan, 0, ',', '.') }}</span>
            </div>
        </div>

        <form action="{{ route('shift.close') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="tunai_fisik" class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Total Tunai Fisik di Laci</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-rose-600 transition-colors">
                            <span class="font-bold">Rp</span>
                        </div>
                        <input type="number" name="tunai_fisik" id="tunai_fisik" min="0" class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-rose-100 focus:bg-white transition-all outline-none" placeholder="Masukkan jumlah yang Anda hitung" required autofocus>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-rose-600 text-white rounded-3xl font-black text-lg shadow-xl shadow-rose-200 hover:bg-rose-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3" onclick="return confirm('Apakah Anda yakin ingin menutup shift ini?')">
                    TUTUP SHIFT & SELESAI
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
