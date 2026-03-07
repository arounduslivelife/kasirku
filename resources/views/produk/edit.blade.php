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
                    <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan <span class="text-rose-500">*</span></label>
                    <div class="mt-2">
                        <input type="text" name="satuan" id="satuan" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all" value="{{ old('satuan', $produk->satuan) }}" required>
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Catatan Tambahan / Keterangan</label>
                    <div class="mt-2">
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
