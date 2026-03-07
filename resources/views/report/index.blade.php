@extends('layout')

@section('content')
<div class="mb-8 bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
    <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Laporan Penjualan</h1>
        <p class="text-slate-500 font-medium mt-1">Export data transaksi ke format Excel secara profesional</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="p-3 bg-emerald-100 text-emerald-600 rounded-2xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
    </div>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 p-10 border border-slate-100">
        <form action="{{ route('report.export') }}" method="POST">
            @csrf
            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label for="start_date" class="block text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-01') }}" class="block w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-100 transition-all outline-none" required>
                    </div>
                    <div class="space-y-3">
                        <label for="end_date" class="block text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" value="{{ date('Y-m-d') }}" class="block w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-100 transition-all outline-none" required>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-5 bg-emerald-600 text-white rounded-3xl font-black text-lg shadow-xl shadow-emerald-100 hover:bg-emerald-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        DOWNLOAD LAPORAN EXCEL
                    </button>
                </div>

                <div class="p-4 bg-indigo-50 rounded-2xl flex gap-4">
                    <div class="shrink-0 text-indigo-400 font-black text-2xl">!</div>
                    <p class="text-xs text-indigo-700 font-medium leading-relaxed">Laporan akan berisi detail transaksi termasuk nomor invoice, nama pelanggan, kasir yang bertugas, total harga, sisa piutang, dan metode pembayaran.</p>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
