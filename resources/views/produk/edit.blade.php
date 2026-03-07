@extends('layout')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Perbarui Data Produk</h1>
            <p class="mt-2 text-sm text-gray-600">Lakukan perubahan pada informasi produk "{{ $produk->nama }}" di bawah ini.</p>
        </div>
        <a href="{{ route('produk.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">
            <svg class="mr-1.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-rose-50 p-4 ring-1 ring-rose-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-rose-800">Terdapat kesalahan pada isian form:</h3>
                    <div class="mt-2 text-sm text-rose-700">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
        <form action="{{ route('produk.update', $produk->id) }}" method="POST" class="p-6 sm:p-8">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Kode Produk -->
                <div>
                    <label for="kode" class="block text-sm font-medium text-gray-700">Kode Produk (Barcode)</label>
                    <div class="mt-2 text-slate-800">
                        <input type="text" name="kode" id="kode" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all" value="{{ old('kode', $produk->kode) }}">
                    </div>
                </div>

                <!-- Nama Barang -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Barang <span class="text-rose-500">*</span></label>
                    <div class="mt-2 text-slate-800">
                        <input type="text" name="nama" id="nama" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all" value="{{ old('nama', $produk->nama) }}" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Harga Beli -->
                    <div>
                        <label for="harga_beli" class="block text-sm font-medium text-gray-700">Harga Beli (Modal) <span class="text-rose-500">*</span></label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="harga_beli" id="harga_beli" min="0" step="0.01" class="block w-full rounded-md border-0 py-2.5 pl-10 pr-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all" value="{{ old('harga_beli', $produk->harga_beli) }}" required>
                        </div>
                    </div>
                    
                    <!-- Harga Jual -->
                    <div>
                        <label for="harga_jual" class="block text-sm font-medium text-gray-700">Harga Jual <span class="text-rose-500">*</span></label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="harga_jual" id="harga_jual" min="0" step="0.01" class="block w-full rounded-md border-0 py-2.5 pl-10 pr-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all" value="{{ old('harga_jual', $produk->harga_jual) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Satuan -->
                <div>
                    <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan Utama <span class="text-rose-500">*</span></label>
                    <div class="mt-2">
                        <input type="text" name="satuan" id="satuan" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all" value="{{ old('satuan', $produk->satuan) }}" required placeholder="Pcs">
                    </div>
                </div>

                <!-- Harga Bertingkat / Multi-Satuan -->
                <div x-data="{ satuans: {{ $produk->satuans->toJson() }} }" class="pt-8 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight">Satuan & Harga Bertingkat</h3>
                            <p class="text-xs text-gray-500 font-medium">Atur konversi untuk penjualan Grosir (misal: 1 Dus = 12 Kg)</p>
                        </div>
                        <button type="button" @click="satuans.push({nama_satuan: '', konversi: '', harga_jual: ''})" class="inline-flex items-center px-4 py-2 bg-indigo-50 border border-indigo-200 text-xs font-bold rounded-xl text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Satuan
                        </button>
                    </div>

                    <div class="bg-gray-50/50 rounded-2xl border border-gray-100 overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <thead class="text-[10px] text-gray-400 font-black uppercase tracking-widest bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 w-1/3">Nama Satuan</th>
                                    <th class="px-6 py-4 w-1/4">Konversi</th>
                                    <th class="px-6 py-4">Harga Jual</th>
                                    <th class="px-6 py-4 text-center w-16">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="(s, index) in satuans" :key="index">
                                    <tr class="bg-transparent hover:bg-white transition-colors group">
                                        <td class="px-6 py-4">
                                            <input type="text" :name="'satuans['+index+'][nama_satuan]'" x-model="s.nama_satuan" class="w-full bg-white border-gray-200 rounded-xl text-sm font-bold text-gray-900 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all shadow-sm" placeholder="Contoh: Dus">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <input type="number" :name="'satuans['+index+'][konversi]'" x-model="s.konversi" class="w-24 bg-white border-gray-200 rounded-xl text-sm font-bold text-gray-900 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all shadow-sm no-spinners" placeholder="12">
                                                <span class="text-[11px] text-gray-400 font-bold italic whitespace-nowrap" x-text="'= ' + formatNumber(s.konversi || 0) + ' ' + (document.getElementById('satuan').value || 'Unit')"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="relative group-hover:scale-[1.02] transition-transform">
                                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 font-bold text-xs pointer-events-none">Rp</span>
                                                <input type="number" :name="'satuans['+index+'][harga_jual]'" x-model="s.harga_jual" class="w-full pl-10 bg-white border-gray-200 rounded-xl text-sm font-black text-indigo-700 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all shadow-sm no-spinners" placeholder="0">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button type="button" @click="satuans.splice(index, 1)" class="p-2 text-gray-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <template x-if="satuans.length === 0">
                            <div class="py-12 text-center">
                                <div class="inline-flex p-4 rounded-full bg-gray-50 text-gray-300 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <p class="text-sm text-gray-400 font-bold italic">Belum ada satuan tambahan diatur.</p>
                            </div>
                        </template>
                    </div>
                </div>

                <script>
                    function formatNumber(val) {
                        return new Intl.NumberFormat('id-ID').format(val);
                    }
                </script>

                <!-- Keterangan -->
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Catatan Tambahan / Keterangan</label>
                    <div class="mt-2 text-slate-800">
                        <textarea name="keterangan" id="keterangan" rows="4" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all">{{ old('keterangan', $produk->keterangan) }}</textarea>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Opsional. Informasi detail terkait produk atau statusnya saat ini.</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-x-4">
                <a href="{{ route('produk.index') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">Batal</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
