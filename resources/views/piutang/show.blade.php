@extends('layout')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Payment Form -->
    <div class="lg:col-span-1 space-y-8">
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 p-8">
            <h2 class="text-xl font-black text-slate-900 mb-6 uppercase tracking-tight">Bayar Cicilan</h2>
            
            <form action="{{ route('piutang.bayar', $penjualan->id) }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="nominal" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Nominal Bayar</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none font-bold text-indigo-600">Rp</div>
                        <input type="number" name="nominal" id="nominal" max="{{ $penjualan->sisa_piutang }}" class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-100 transition-all outline-none" placeholder="0" required>
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold italic mt-1 ml-1">* Maksimal: Rp {{ number_format($penjualan->sisa_piutang, 0, ',', '.') }}</p>
                </div>

                <div class="space-y-2">
                    <label for="tanggal" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Tanggal Bayar</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" class="block w-full px-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-100 transition-all outline-none" required>
                </div>

                <div class="space-y-2">
                    <label for="keterangan" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="block w-full px-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 font-medium focus:ring-4 focus:ring-indigo-100 transition-all outline-none" placeholder="Opsional..."></textarea>
                </div>

                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-3xl font-black text-lg shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center justify-center gap-3">
                    SIMPAN PEMBAYARAN
                </button>
            </form>
        </div>

        <div class="bg-indigo-900 rounded-[2.5rem] shadow-xl p-8 text-white">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest leading-none mb-1">Pelanggan</div>
                    <div class="text-lg font-black tracking-tight leading-none">{{ $penjualan->pelanggan->nama ?? 'Umum' }}</div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm border-b border-white/10 pb-4">
                    <span class="text-indigo-300 font-bold uppercase tracking-wider">Total Struk</span>
                    <span class="font-black">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-base">
                    <span class="text-indigo-300 font-bold uppercase tracking-wider">Sisa Hutang</span>
                    <span class="text-2xl font-black text-rose-400">Rp {{ number_format($penjualan->sisa_piutang, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- History & Details -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
            <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-base font-black text-slate-900 uppercase tracking-widest">Riwayat Pembayaran</h2>
                <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $pembayarans->count() }} Kali Bayar</span>
            </div>
            <div class="p-8">
                <div class="space-y-4">
                    @forelse($pembayarans as $bayar)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-indigo-100 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-900 tracking-tight">Rp {{ number_format($bayar->nominal, 0, ',', '.') }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $bayar->tanggal }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-400 font-medium italic block">{{ $bayar->keterangan ?? 'Tanpa keterangan' }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 opacity-30">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="font-bold uppercase tracking-widest text-xs">Belum ada cicilan masuk</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden px-8 py-8">
            <h2 class="text-base font-black text-slate-900 uppercase tracking-widest mb-6">Detail Belanja Awal</h2>
            <div class="space-y-4">
                @foreach($penjualan->details as $item)
                <div class="flex justify-between items-center py-3 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 px-2 rounded-xl transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-slate-100 text-slate-400 rounded-lg flex items-center justify-center font-bold text-xs">{{ $loop->iteration }}</div>
                        <div>
                            <div class="text-sm font-bold text-slate-800">{{ $item->produk->nama }}</div>
                            <div class="text-[10px] text-slate-400 font-medium tracking-wide italic">{{ number_format($item->qty, 0, ',', '.') }} x Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="text-sm font-black text-slate-900 tracking-tighter">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
