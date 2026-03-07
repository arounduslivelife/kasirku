@extends('layout')

@section('content')
<div class="mb-8 flex justify-between items-center bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Pelanggan Piutang</h1>
        <p class="text-slate-500 font-medium">Kelola saldo piutang dan penagihan pelanggan</p>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden px-4 py-4">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-separate border-spacing-y-3">
            <thead>
                <tr class="text-slate-400 text-xs font-black uppercase tracking-[0.2em]">
                    <th class="px-6 py-4">Invoice</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Sisa Piutang</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($piutangs as $piutang)
                <tr class="bg-slate-50 hover:bg-white hover:shadow-xl hover:shadow-indigo-100/50 transition-all group rounded-2xl">
                    <td class="px-6 py-5 first:rounded-l-2xl">
                        <span class="font-bold text-slate-900">{{ $piutang->nomor_invoice }}</span>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $piutang->tanggal }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="font-bold text-indigo-600 uppercase">{{ $piutang->pelanggan->nama ?? 'Umum' }}</span>
                    </td>
                    <td class="px-6 py-5">
                        <span class="font-medium text-slate-500">Rp {{ number_format($piutang->total_harga, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-xl font-black text-sm">
                            Rp {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-5 last:rounded-r-2xl">
                        <a href="{{ route('piutang.show', $piutang->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-black shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                            BAYAR / DETAIL
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Tidak ada piutang aktif</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 px-4">
        {{ $piutangs->links() }}
    </div>
</div>
@endsection
