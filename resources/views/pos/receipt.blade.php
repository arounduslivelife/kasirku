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
        <div class="mt-10 flex flex-wrap gap-4 w-full no-print">
            <button onclick="window.print()" class="flex-1 min-w-[140px] px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Struk Berapa?
            </button>
            
            @php
                $waText = "Halo, ini adalah rincian pesanan Anda di PRODUK ASSCO:\n\n" .
                         "Invoice: " . $penjualan->nomor_invoice . "\n" .
                         "Tanggal: " . $penjualan->created_at->format('d/m/Y H:i') . "\n\n";
                         
                foreach($penjualan->details as $detail) {
                    $waText .= "- " . $detail->produk->nama . " x" . number_format($detail->qty, 0, ',', '.') . " : Rp " . number_format($detail->subtotal, 0, ',', '.') . "\n";
                }
                
                $waText .= "\nTOTAL: Rp " . number_format($penjualan->total_harga, 0, ',', '.') . "\n";
                $waText .= "Status: " . ($penjualan->status_bayar == 'Piutang' ? 'BELUM LUNAS (Bon/Tempo)' : 'LUNAS') . "\n\n";
                $waText .= "Terima kasih!";
                $waUrl = "https://wa.me/" . ($penjualan->pelanggan->telepon ?? '') . "?text=" . urlencode($waText);
            @endphp

            <a href="{{ $waUrl }}" target="_blank" class="flex-1 min-w-[140px] px-4 py-3 bg-emerald-500 text-white rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.432 L.157 24.521l7.46-1.956c1.398.761 2.978 1.162 4.588 1.163.003 0 .006 0 .009 0 6.554 0 11.89-5.335 11.892-11.892a11.801 11.801 0 00-3.483-8.431z"/></svg>
                WhatsApp
            </a>

            <a href="{{ route('pos.index') }}" class="flex-1 min-w-[140px] px-4 py-3 bg-white text-gray-600 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-gray-50 transition-all border border-gray-200">
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
