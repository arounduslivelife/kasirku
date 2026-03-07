@extends('layout')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Edit Catatan Biaya</h1>
        <p class="mt-2 text-sm text-gray-600">Perbarui data pengeluaran operasional yang salah input.</p>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
        <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST" class="p-6 sm:p-8">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Pengeluaran <span class="text-rose-500">*</span></label>
                    <div class="mt-2 text-slate-800">
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $pengeluaran->tanggal) }}" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm" required>
                    </div>
                </div>

                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori <span class="text-rose-500">*</span></label>
                    <div class="mt-2 text-slate-800">
                        <select name="kategori" id="kategori" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm" required>
                            @foreach(['Listrik & Air', 'Gaji Karyawan', 'Sewa Tempat', 'Operasional Toko', 'Perlengkapan', 'Lain-lain'] as $cat)
                                <option value="{{ $cat }}" {{ $pengeluaran->kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="nominal" class="block text-sm font-medium text-gray-700">Nominal Pengeluaran <span class="text-rose-500">*</span></label>
                    <div class="mt-2 relative rounded-md shadow-sm text-slate-800">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="nominal" id="nominal" min="0" value="{{ old('nominal', $pengeluaran->nominal) }}" class="block w-full rounded-md border-0 py-2.5 pl-10 pr-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm" placeholder="0" required>
                    </div>
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan Tambahan</label>
                    <div class="mt-2 text-slate-800">
                        <textarea name="keterangan" id="keterangan" rows="3" class="block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm" placeholder="Misal: Bayar listrik bulan Maret">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-x-4">
                <a href="{{ route('pengeluaran.index') }}" class="text-sm font-semibold text-gray-900">Batal</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-600 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
