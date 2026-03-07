@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto" x-data="stockEntry()">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Catat Stok Masuk (Borongan)</h1>
            <p class="mt-2 text-sm text-gray-600">Input beberapa produk sekaligus untuk menambah stok master.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('invin.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" form="bulk-invin-form" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-semibold rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Semua Transaksi
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

    <form id="bulk-invin-form" action="{{ route('invin.store') }}" method="POST">
        @csrf
        
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="pemasok_id" class="block text-sm font-medium text-gray-700">Pemasok / Supplier (Global untuk semua item ini)</label>
                        <select name="pemasok_id" id="pemasok_id" class="mt-1 block w-full rounded-md border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option value="">-- Opsional --</option>
                            @foreach($pemasoks as $pemasok)
                                <option value="{{ $pemasok->id }}">{{ $pemasok->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Transaksi (Global)</label>
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
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-48">Harga Beli (@)</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subtotal</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <select :name="`items[${index}][produk_id]`" x-model="item.produk_id" @change="updatePrice(index)" class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm transition-all" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($produks as $p)
                                            <option value="{{ $p->id }}" data-price="{{ $p->harga_beli }}">{{ $p->nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" step="0.01" min="0.01" :name="`items[${index}][qty]`" x-model="item.qty" class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm" required>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" :name="`items[${index}][harga_beli]`" x-model="item.harga_beli" class="block w-full rounded-md border-0 py-2 pl-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm" required>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    Rp <span x-text="formatNumber(item.qty * item.harga_beli)"></span>
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
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-700">Total Kuantitas: <span x-text="totalQty()"></span></td>
                            <td class="px-6 py-4 text-sm font-bold text-emerald-700">Total Biaya: Rp <span x-text="totalCost()"></span></td>
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
    function stockEntry() {
        return {
            items: [
                { produk_id: '', qty: 1, harga_beli: 0 }
            ],
            addItem() {
                this.items.push({ produk_id: '', qty: 1, harga_beli: 0 });
            },
            removeItem(index) {
                this.items.splice(index, 1);
            },
            updatePrice(index) {
                const select = event.target;
                const price = select.options[select.selectedIndex].getAttribute('data-price');
                if (price) {
                    this.items[index].harga_beli = price;
                }
            },
            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num || 0);
            },
            totalQty() {
                return this.items.reduce((sum, item) => sum + parseFloat(item.qty || 0), 0);
            },
            totalCost() {
                let total = this.items.reduce((sum, item) => sum + (parseFloat(item.qty || 0) * parseFloat(item.harga_beli || 0)), 0);
                return this.formatNumber(total);
            }
        }
    }
</script>
@endsection
