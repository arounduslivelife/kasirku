@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Pengaturan Aplikasi</h1>
        <p class="mt-2 text-sm text-gray-600">Konfigurasikan sistem dasar operasional gudang Anda.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-emerald-50 p-4 ring-1 ring-emerald-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden mb-8">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Metode Harga Pokok Penjualan (HPP)</h2>
            <p class="text-sm text-gray-500 mt-1">Pilih metode akuntansi yang digunakan untuk menghitung nilai modal barang saat dijual.</p>
        </div>
        
        <form action="{{ route('settings.update') }}" method="POST" class="p-6 sm:p-8">
            @csrf
            
            <div class="space-y-6">
                <!-- AVERAGE -->
                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none transition-all {{ $hppMethod === 'AVERAGE' ? 'border-indigo-600 ring-1 ring-indigo-600' : 'border-gray-300 hover:border-indigo-400' }}">
                    <input type="radio" name="hpp_method" value="AVERAGE" class="sr-only" {{ $hppMethod === 'AVERAGE' ? 'checked' : '' }} onchange="this.form.submit()">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span class="block text-sm font-medium text-gray-900">Moving Average (Rata-Rata Berjalan)</span>
                            <span class="mt-1 flex items-center text-sm text-gray-500">Nilai modal dihitung secara proporsional rata-rata setiap ada barang masuk baru. Disarankan untuk operasional standar rata-rata toko.</span>
                        </span>
                    </span>
                    <svg class="h-6 w-6 {{ $hppMethod === 'AVERAGE' ? 'text-indigo-600' : 'text-transparent' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </label>

                <!-- FIFO -->
                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none transition-all {{ $hppMethod === 'FIFO' ? 'border-indigo-600 ring-1 ring-indigo-600' : 'border-gray-300 hover:border-indigo-400' }}">
                    <input type="radio" name="hpp_method" value="FIFO" class="sr-only" {{ $hppMethod === 'FIFO' ? 'checked' : '' }} onchange="this.form.submit()">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span class="block text-sm font-medium text-gray-900">FIFO (First-In First-Out)</span>
                            <span class="mt-1 flex items-center text-sm text-gray-500">Barang yang pertama kali masuk diasumsikan sebagai barang yang pertama kali keluar. Harga pokok dihitung secara presisi berdasarkan batch tiap transaksi masuk.</span>
                        </span>
                    </span>
                    <svg class="h-6 w-6 {{ $hppMethod === 'FIFO' ? 'text-indigo-600' : 'text-transparent' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </label>
            </div>
            
            <div class="mt-6 text-sm text-amber-600 bg-amber-50 p-4 rounded-lg flex items-start">
                <svg class="h-5 w-5 mr-2 pt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span><strong>Perhatian:</strong> Mengubah metode perhitungan HPP (Harga Pokok Penjualan) tidak akan merubah data perhitungan historis rekapan HPP pada transaksi stok keluar yang sudah terjadi sebelumnya. Perubahan pengaturan ini hanya akan berefek pada transaksi stok keluar baru yang dicatat sejak saat metode ini diubah.</span>
            </div>
        </form>
    </div>
</div>
@endsection
