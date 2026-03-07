@extends('layout')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Tambah Pemasok Baru</h1>
            <p class="mt-2 text-sm text-gray-600">Lengkapi data di bawah ini untuk menambahkan pemasok / supplier baru.</p>
        </div>
        <a href="{{ route('pemasok.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">
            <svg class="mr-1.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
        <form action="{{ route('pemasok.store') }}" method="POST" class="p-6 sm:p-8">
            @csrf
            <div class="space-y-6">
                
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Pemasok <span class="text-rose-500">*</span></label>
                    <div class="mt-2">
                        <input type="text" name="nama" id="nama" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all @error('nama') ring-rose-300 @enderror" value="{{ old('nama') }}" required placeholder="Contoh: PT. Sumber Jaya">
                    </div>
                    @error('nama') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="telepon" class="block text-sm font-medium text-gray-700">No. Telepon / WhatsApp</label>
                    <div class="mt-2">
                        <input type="text" name="telepon" id="telepon" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all @error('telepon') ring-rose-300 @enderror" value="{{ old('telepon') }}" placeholder="Contoh: 08123456789">
                    </div>
                    @error('telepon') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <div class="mt-2">
                        <textarea name="alamat" id="alamat" rows="3" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all @error('alamat') ring-rose-300 @enderror" placeholder="Contoh: Jl. Industri No. 45, Karawang">{{ old('alamat') }}</textarea>
                    </div>
                    @error('alamat') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-x-4">
                <a href="{{ route('pemasok.index') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">Batal</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                    Simpan Pemasok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
