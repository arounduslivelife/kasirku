@extends('layout')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Riwayat Stok Keluar</h1>
        <p class="mt-2 text-sm text-gray-600">Catat dan pantau semua pengeluaran barang dari gudang.</p>
    </div>
    <a href="{{ route('invout.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-rose-600 hover:bg-rose-700 shadow-sm transition-colors duration-200">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
        Catat Stok Keluar
    </a>
</div>

<div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/50">
                <tr>
                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga Jual</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">HPP (COGS)</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty Keluar</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right font-semibold text-gray-500 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($invouts as $item)
                <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-gray-900 font-medium">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                        {{ $item->produk ? $item->produk->nama : 'Produk Tidak Ditemukan' }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">
                        {{ $item->pelanggan ? $item->pelanggan->nama : '-' }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-emerald-600 font-medium">
                        Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-rose-600 font-medium tooltip" title="Harga Pokok Penjualan saat rilis">
                        Rp {{ number_format($item->hpp, 0, ',', '.') }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-rose-600 font-bold">
                        - {{ number_format($item->qty, 0, ',', '.') }} {{ $item->produk ? $item->produk->satuan : '' }}
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">
                        {{ $item->keterangan ?: '-' }}
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                        <form action="{{ route('invout.destroy', $item->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Menghapus riwayat Stok Keluar akan MENAMBAH kembali stok pada master produk. Lanjutkan?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:text-rose-900 transition-colors tooltip" title="Hapus Riwayat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <h3 class="text-sm font-medium text-gray-900">Belum ada riwayat Stok Keluar</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan mencatat pengeluaran barang dari gudang.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
