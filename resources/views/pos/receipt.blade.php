@extends('layout')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="bg-white p-8 shadow-sm ring-1 ring-gray-900/5 rounded-xl flex flex-col items-center">
        <!-- Receipt Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-black text-gray-900 uppercase tracking-widest">PRODUK ASSCO</h1>
            <p class="text-sm text-gray-500">Jl. Raya No. 123, Kota Anda</p>
            <p class="text-sm text-gray-500">Telp: 0812-3456-7890</p>
        </div>

        <div class="w-full border-t border-dashed border-gray-200 py-4 text-xs font-mono text-gray-600">
            <div class="flex justify-between">
                <span>Invoice:</span>
                <span>{{ $penjualan->nomor_invoice }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tanggal:</span>
                <span>{{ $penjualan->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kasir:</span>
                <span>{{ $penjualan->user->name }}</span>
            </div>
            <div class="flex justify-between text-gray-900 font-bold mt-1">
                <span>Pelanggan:</span>
                <span>{{ $penjualan->pelanggan->nama ?? 'Umum (Walk-in)' }}</span>
            </div>
        </div>

        <!-- Items Table -->
        <table class="w-full text-xs font-mono mb-6">
            <thead>
                <tr class="border-y border-dashed border-gray-200">
                    <th class="py-2 text-left">Item</th>
                    <th class="py-2 text-center">Qty</th>
                    <th class="py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->details as $detail)
                <tr class="align-top">
                    <td class="py-2 pr-2">
                        <div>{{ $detail->produk->nama }}</div>
                        <div class="text-[10px] text-gray-400">@ {{ number_format($detail->harga_jual, 0, ',', '.') }}</div>
                    </td>
                    <td class="py-2 text-center">{{ number_format($detail->qty, 0, ',', '.') }}</td>
                    <td class="py-2 text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="w-full border-t border-dashed border-gray-200 pt-4 space-y-1 text-sm">
            <div class="flex justify-between font-bold text-lg text-gray-900">
                <span>TOTAL</span>
                <span>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Bayar ({{ $penjualan->metode_bayar }})</span>
                <span>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-900 font-bold border-t border-gray-100 pt-1">
                <span>Kembali</span>
                <span>Rp {{ number_format($penjualan->kembali, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="mt-8 text-center text-xs text-gray-400 italic">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar.</p>
        </div>

        <!-- Action Buttons (Hidden when printing) -->
        <div class="mt-10 flex gap-4 w-full no-print">
            <button onclick="window.print()" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Struk
            </button>
            <a href="{{ route('pos.index') }}" class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-gray-200 transition-all border border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Baru
            </a>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .no-print { display: none !important; }
        .bg-white { box-shadow: none !important; ring: none !important; }
        .max-w-md { max-width: 100% !important; margin: 0 !important; width: 100% !important; }
        .content-area { margin: 0 !important; padding: 0 !important; }
        .print-container, .print-container * { visibility: visible; }
        .print-container { position: absolute; left: 0; top: 0; width: 58mm; }
    }
</style>
@endsection
