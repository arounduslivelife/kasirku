@extends('layout')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Riwayat Penjualan (POS)</h1>
    <p class="mt-2 text-sm text-gray-600">Daftar seluruh transaksi penjulan yang telah diproses.</p>
</div>

<div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/50">
                <tr>
                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Invoice</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kasir</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right font-semibold text-gray-500 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($sales as $item)
                <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-mono font-bold text-indigo-600">
                        {{ $item->nomor_invoice }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ $item->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-medium">
                        {{ $item->user->name }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">
                        {{ $item->pelanggan->nama ?? 'Umum' }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-black text-gray-900">
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            Berhasil
                        </span>
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                        <a href="{{ route('pos.receipt', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 flex justify-end gap-1 items-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Lihat Struk
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $sales->links() }}
</div>
@endsection
