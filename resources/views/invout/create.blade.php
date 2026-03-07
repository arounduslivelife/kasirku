@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto" x-data="stockExit()">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Catat Stok Keluar (Borongan)</h1>
            <p class="mt-2 text-sm text-gray-600">Pilih beberapa barang sekaligus untuk dikurangi dari stok master.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('invout.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" form="bulk-invout-form" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-semibold rounded-md shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Stok Keluar
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-rose-50 p-4 ring-1 ring-rose-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-rose-800">Terdapat kesalahan pada input:</h3>
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

    <form id="bulk-invout-form" action="{{ route('invout.store') }}" method="POST">
        @csrf
        
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="pelanggan_id" class="block text-sm font-medium text-gray-700">Pelanggan / Konsumen (Global)</label>
                        <select name="pelanggan_id" id="pelanggan_id" class="mt-1 block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option value="">-- Opsional --</option>
                            @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Keluar (Global)</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm" required>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/3">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-48">Harga Jual (@)</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subtotal</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <select :name="`items[${index}][produk_id]`" x-model="item.produk_id" @change="updatePrice(index)" class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-rose-600 sm:text-sm transition-all" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($produks as $p)
                                            <option value="{{ $p->id }}" data-price="{{ $p->harga_jual }}" data-stock="{{ $p->jumlah }}" data-unit="{{ $p->satuan }}">{{ $p->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-1 text-xs text-gray-500" x-show="item.max_stock > 0">
                                        Stok tersedia: <span class="font-bold" x-text="item.max_stock"></span> <span x-text="item.unit"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" step="0.01" min="0.01" :max="item.max_stock" :name="`items[${index}][qty]`" x-model="item.qty" class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-rose-600 sm:text-sm" required>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" :name="`items[${index}][harga_jual]`" x-model="item.harga_jual" class="block w-full rounded-md border-0 py-2 pl-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-rose-600 sm:text-sm" required>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    Rp <span x-text="formatNumber(item.qty * item.harga_jual)"></span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button type="button" @click="removeItem(index)" class="text-rose-500 hover:text-rose-700 transition-colors" x-show="items.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-gray-50/50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-700">Total Produk: <span x-text="items.length"></span></td>
                            <td class="px-6 py-4 text-sm font-bold text-rose-700">Total Penjualan: Rp <span x-text="totalSales()"></span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="p-6 bg-gray-50/50 border-t border-gray-100">
                <button type="button" @click="addItem()" class="inline-flex items-center px-4 py-2 border border-indigo-600 shadow-sm text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Baris Barang
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function stockExit() {
        return {
            items: [
                { produk_id: '', qty: 1, harga_jual: 0, max_stock: 0, unit: '' }
            ],
            addItem() {
                this.items.push({ produk_id: '', qty: 1, harga_jual: 0, max_stock: 0, unit: '' });
            },
            removeItem(index) {
                this.items.splice(index, 1);
            },
            updatePrice(index) {
                const select = event.target;
                const option = select.options[select.selectedIndex];
                const price = option.getAttribute('data-price');
                const stock = option.getAttribute('data-stock');
                const unit = option.getAttribute('data-unit');
                
                if (price) {
                    this.items[index].harga_jual = price;
                    this.items[index].max_stock = stock;
                    this.items[index].unit = unit;
                }
            },
            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num || 0);
            },
            totalSales() {
                let total = this.items.reduce((sum, item) => sum + (parseFloat(item.qty || 0) * parseFloat(item.harga_jual || 0)), 0);
                return this.formatNumber(total);
            }
        }
    }
</script>
@endsection
