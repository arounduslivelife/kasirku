@extends('layout')

@section('content')
<div class="space-y-8" x-data="{ }">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Omzet Hari Ini</p>
                    <h3 class="text-2xl font-black text-gray-900">Rp {{ number_format($omzetHariIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Estimasi Laba</p>
                    <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($profitHariIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Transaksi</p>
                    <h3 class="text-2xl font-black text-gray-900">{{ $trxHariIni }} Nota</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-rose-100 text-rose-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Stok Menipis</p>
                    <h3 class="text-2xl font-black text-rose-600">{{ $criticalStocks->count() }} Produk</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm ring-1 ring-gray-900/5">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Tren Penjualan (7 Hari Terakhir)</h3>
            <div class="h-64 flex items-end justify-between gap-4 px-4">
                @foreach($chartData as $data)
                <div class="flex-1 flex flex-col items-center gap-2 group">
                    <div class="w-full bg-indigo-100 rounded-lg group-hover:bg-indigo-600 transition-all duration-300 relative" 
                         style="height: {{ ($data->total / max($chartData->pluck('total')->toArray() ?: [1])) * 100 }}%">
                         <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap z-10 transition-opacity">
                            Rp {{ number_format($data->total, 0, ',', '.') }}
                         </div>
                    </div>
                    <span class="text-[10px] font-bold text-gray-400 rotate-45 mt-4">{{ date('d/m', strtotime($data->tanggal)) }}</span>
                </div>
                @endforeach
                @if($chartData->isEmpty())
                <div class="w-full h-full flex items-center justify-center text-gray-300 italic">Belum ada data penjualan 7 hari terakhir</div>
                @endif
            </div>
        </div>

        <!-- Critical Stocks -->
        <div class="bg-white p-6 rounded-2xl shadow-sm ring-1 ring-gray-900/5 flex flex-col">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Peringatan Stok</h3>
            <div class="flex-1 space-y-4">
                @forelse($criticalStocks as $item)
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center font-bold text-xs">
                            {{ $item->jumlah }}
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 line-clamp-1 truncate w-40">{{ $item->nama }}</h4>
                            <p class="text-[10px] text-gray-400">{{ $item->satuan }}</p>
                        </div>
                    </div>
                    <a href="{{ route('invin.create') }}" class="text-xs text-indigo-600 font-bold hover:underline">Restok</a>
                </div>
                @empty
                <div class="h-full flex items-center justify-center text-gray-300 italic text-sm">Semua stok aman ( > 5 )</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Trans -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Penjualan Terbaru</h3>
                <a href="{{ route('pos.history') }}" class="text-xs text-indigo-600 font-bold hover:underline">Lihat Semua</a>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase">Nota</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-400 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentSales as $sale)
                    <tr>
                        <td class="px-6 py-4 text-xs font-mono font-bold text-indigo-600">{{ $sale->nomor_invoice }}</td>
                        <td class="px-6 py-4 text-xs font-medium text-gray-700">{{ $sale->user->name }}</td>
                        <td class="px-6 py-4 text-xs text-right font-black text-gray-900">Rp {{ number_format($sale->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-6">
            <a href="{{ route('pos.index') }}" class="bg-indigo-600 p-8 rounded-3xl text-white flex flex-col items-center justify-center gap-4 hover:bg-indigo-700 transition-all group">
                <div class="p-4 bg-white/20 rounded-2xl group-hover:scale-110 transition-transform">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <span class="text-xl font-black">BUKA KASIR</span>
            </a>
            <a href="{{ route('produk.create') }}" class="bg-white p-8 rounded-3xl text-gray-900 shadow-sm ring-1 ring-gray-900/5 flex flex-col items-center justify-center gap-4 hover:shadow-xl transition-all group">
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:scale-110 transition-transform">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-xl font-black">TAMBAH PRODUK</span>
            </a>
        </div>
    </div>
</div>
@endsection
