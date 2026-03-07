@extends('layout')

@section('content')
<div class="h-[calc(100vh-120px)] flex flex-col md:flex-row gap-6" x-data="posSystem()">
    <!-- Bagian Kiri: Pencarian & Daftar Produk -->
    <div class="w-full md:w-3/5 bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" 
                       x-model="search" 
                       @keyup.enter="handleBarcode()" 
                       class="block w-full pl-10 pr-3 py-3 border-0 rounded-xl text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm" 
                       placeholder="Cari Nama Produk atau Scan Barcode (Enter)..." 
                       autofocus>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div @click="addToCart(product)" 
                         class="group cursor-pointer bg-white border border-gray-200 rounded-xl p-4 hover:border-indigo-600 hover:shadow-md transition-all duration-200 relative overflow-hidden">
                        <div class="absolute top-2 right-2 flex flex-col gap-1 items-end">
                             <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600" x-text="product.satuan"></span>
                        </div>
                        <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 pr-6" x-text="product.nama"></h4>
                        <p class="text-xs text-gray-400 mt-1" x-text="'Kode: ' + (product.kode || '-')"></p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-indigo-600 font-extrabold text-sm" x-text="formatCurrency(product.harga_jual)"></span>
                            <span :class="product.jumlah <= 5 ? 'text-rose-600' : 'text-gray-500'" class="text-[10px] font-medium">Stok: <span x-text="formatNumber(product.jumlah)"></span></span>
                        </div>
                    </div>
                </template>
            </div>
            <div x-show="filteredProducts.length === 0" class="flex flex-col items-center justify-center h-full text-gray-400">
                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>Produk tidak ditemukan</p>
            </div>
        </div>
    </div>

    <!-- Bagian Kanan: Keranjang Pembelian -->
    <div class="w-full md:w-2/5 flex flex-col gap-6">
        <div class="flex-1 bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-900 text-white flex justify-between items-center">
                <h3 class="font-bold flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Keranjang Belanja
                </h3>
                <button @click="cart = []" class="text-xs text-gray-400 hover:text-white transition-colors">Kosongkan</button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 custom-scrollbar bg-gray-50/30">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center justify-between mb-4 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex-1 pr-4">
                            <h5 class="text-sm font-bold text-gray-900 line-clamp-1" x-text="item.nama"></h5>
                            <div class="flex items-center gap-1 mt-0.5">
                                <template x-if="item.satuans && item.satuans.length > 0">
                                    <select @change="changeSatuan(index, $event.target.value)" class="text-[9px] py-0 px-1 border-gray-200 rounded bg-gray-50 font-bold uppercase">
                                        <option :value="item.satuan_asli" x-text="item.satuan_asli" :selected="item.satuan_dipilih === item.satuan_asli"></option>
                                        <template x-for="s in item.satuans" :key="s.id">
                                            <option :value="s.nama_satuan" x-text="s.nama_satuan" :selected="item.satuan_dipilih === s.nama_satuan"></option>
                                        </template>
                                    </select>
                                </template>
                                <template x-if="!item.satuans || item.satuans.length === 0">
                                    <span class="text-[9px] text-gray-500 font-bold uppercase" x-text="item.satuan_dipilih"></span>
                                </template>
                                <span class="text-[9px] text-gray-400 font-medium" x-text="'@ ' + formatCurrency(item.harga_jual)"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center bg-gray-100 rounded-lg p-1">
                                <button @click="updateQty(index, -1)" class="w-6 h-6 flex items-center justify-center text-gray-600 hover:bg-white rounded-md transition-all">-</button>
                                <input type="number" x-model="item.qty" @input="validateQty(index)" class="w-10 text-center bg-transparent border-0 focus:ring-0 text-sm font-bold text-gray-900 no-spinners">
                                <button @click="updateQty(index, 1)" class="w-6 h-6 flex items-center justify-center text-gray-600 hover:bg-white rounded-md transition-all">+</button>
                            </div>
                            <div class="text-right min-w-[100px]">
                                <template x-if="item.diskon > 0">
                                    <div class="text-[10px] text-rose-500 font-bold line-through" x-text="formatCurrency(item.qty * item.harga_jual)"></div>
                                </template>
                                <span class="text-sm font-bold text-indigo-600" x-text="formatCurrency((item.qty * item.harga_jual) - item.diskon)"></span>
                                <template x-if="item.nama_promo">
                                    <div class="text-[9px] text-emerald-600 font-black uppercase tracking-tighter" x-text="item.nama_promo"></div>
                                </template>
                            </div>
                            <button @click="removeFromCart(index)" class="text-rose-500 hover:text-rose-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>
                <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-full opacity-30 italic text-gray-600 py-10">
                    <p>Keranjang masih kosong</p>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-200">
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span x-text="formatCurrency(totalCart())"></span>
                    </div>
                    <div class="flex justify-between text-2xl font-black text-gray-900 pt-2 border-t border-gray-200">
                        <span>TOTAL</span>
                        <span class="text-indigo-600" x-text="formatCurrency(totalCart())"></span>
                    </div>
                </div>
                
                <button @click="openPaymentModal()" 
                        :disabled="cart.length === 0"
                        class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:scale-100 disabled:shadow-none">
                    BAYAR SEKARANG
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran -->
    <div x-show="paymentModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition.opacity>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden" @click.away="paymentModal = false">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-black text-gray-900">Detail Pembayaran</h3>
                <button @click="paymentModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-8">
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Pilih Pelanggan</label>
                    <select x-model="pelanggan_id" class="w-full rounded-xl border-gray-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600">
                        <option value="">-- Umum (Walk-in) --</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-indigo-50 p-6 rounded-2xl mb-8 flex justify-between items-center">
                    <span class="text-gray-600 font-bold text-sm">Total Belanja</span>
                    <span class="text-3xl font-black text-indigo-700" x-text="formatCurrency(totalCart())"></span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Metode Bayar</label>
                        <select x-model="metode_bayar" class="w-full rounded-xl border-gray-200 shadow-sm focus:ring-indigo-600">
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Piutang">Piutang (Tempo)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Uang Dibayar</label>
                        <input type="number" x-model="bayar" class="w-full rounded-xl border-gray-200 shadow-sm focus:ring-indigo-600 text-lg font-bold no-spinners">
                    </div>
                </div>

                <div class="p-6 rounded-2xl flex justify-between items-center" :class="bayar - totalCart() >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'">
                    <span class="font-bold" x-text="bayar - totalCart() >= 0 ? 'Kembalian' : 'Sisa Hutang'"></span>
                    <span class="text-2xl font-black" x-text="formatCurrency(Math.abs(bayar - totalCart()))"></span>
                </div>
            </div>
            <div class="p-6 bg-gray-50 flex gap-4">
                <button @click="paymentModal = false" class="flex-1 py-4 font-bold text-gray-600 hover:bg-white rounded-2xl transition-all border border-transparent hover:border-gray-200">Batal</button>
                <button @click="processPayment()" 
                        :disabled="processing || (metode_bayar !== 'Piutang' && bayar < totalCart()) || (metode_bayar === 'Piutang' && !pelanggan_id)"
                        class="flex-1 py-4 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all disabled:opacity-50">
                    <span x-show="!processing">PROSES & CETAK</span>
                    <span x-show="processing">Memproses...</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }
</style>

<script>
    function posSystem() {
        return {
            search: '',
            products: @json($produks),
            promos: @json(\App\Models\Promo::where('is_active', true)->get()),
            cart: [],
            paymentModal: false,
            processing: false,
            pelanggan_id: '',
            metode_bayar: 'Tunai',
            bayar: 0,

            get filteredProducts() {
                if (this.search === '') return this.products;
                return this.products.filter(p => 
                    p.nama.toLowerCase().includes(this.search.toLowerCase()) || 
                    (p.kode && p.kode.toLowerCase().includes(this.search.toLowerCase()))
                );
            },

            handleBarcode() {
                const product = this.products.find(p => p.kode === this.search);
                if (product) {
                    this.addToCart(product);
                    this.search = '';
                }
            },

            addToCart(product) {
                const existing = this.cart.find(c => c.produk_id === product.id);
                if (existing) {
                    if (existing.qty < product.jumlah) {
                        existing.qty++;
                    } else {
                        alert('Stok tidak mencukupi');
                    }
                } else {
                    this.cart.push({
                        produk_id: product.id,
                        nama: product.nama,
                        harga_jual: product.harga_jual,
                        satuan_asli: product.satuan,
                        satuan_dipilih: product.satuan,
                        satuans: product.satuans || [],
                        konversi: 1,
                        produk_satuan_id: null,
                        harga_asli: product.harga_jual,
                        harga_jual: product.harga_jual,
                        qty: 1,
                        stock: product.jumlah,
                        diskon: 0,
                        promo_id: null,
                        nama_promo: null
                    });
                }
                this.recalculatePromos();
            },

            changeSatuan(index, nama_satuan) {
                const item = this.cart[index];
                if (nama_satuan === item.satuan_asli) {
                    item.satuan_dipilih = item.satuan_asli;
                    item.harga_jual = item.harga_asli;
                    item.konversi = 1;
                    item.produk_satuan_id = null;
                } else {
                    const sat = item.satuans.find(s => s.nama_satuan === nama_satuan);
                    if (sat) {
                        item.satuan_dipilih = sat.nama_satuan;
                        item.harga_jual = sat.harga_jual;
                        item.konversi = sat.konversi;
                        item.produk_satuan_id = sat.id;
                    }
                }
                this.recalculatePromos();
            },

            recalculatePromos() {
                this.cart.forEach(item => {
                    const today = new Date().toISOString().split('T')[0];
                    const activePromos = this.promos.filter(p => 
                        p.produk_id === item.produk_id && 
                        p.min_beli <= item.qty &&
                        (!p.start_date || p.start_date <= today) &&
                        (!p.end_date || p.end_date >= today)
                    ).sort((a, b) => b.min_beli - a.min_beli);

                    if (activePromos.length > 0) {
                        const promo = activePromos[0];
                        let diskonPerItem = 0;
                        if (promo.tipe === 'persen') {
                            diskonPerItem = (promo.nilai / 100) * item.harga_jual;
                        } else {
                            diskonPerItem = promo.nilai;
                        }
                        item.diskon = diskonPerItem * item.qty;
                        item.promo_id = promo.id;
                        item.nama_promo = promo.nama;
                    } else {
                        item.diskon = 0;
                        item.promo_id = null;
                        item.nama_promo = null;
                    }
                });
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            updateQty(index, amount) {
                const item = this.cart[index];
                const newQty = item.qty + amount;
                if (newQty > 0 && newQty <= item.stock) {
                    item.qty = newQty;
                    this.recalculatePromos();
                }
            },

            validateQty(index) {
                const item = this.cart[index];
                if (item.qty > item.stock) item.qty = item.stock;
                if (item.qty < 0.01) item.qty = 0.01;
                this.recalculatePromos();
            },

            totalCart() {
                return this.cart.reduce((sum, item) => sum + (item.qty * item.harga_jual) - item.diskon, 0);
            },

            formatNumber(val) {
                return new Intl.NumberFormat('id-ID').format(val);
            },

            formatCurrency(amount) {
                return 'Rp ' + this.formatNumber(amount);
            },

            openPaymentModal() {
                this.bayar = this.totalCart();
                this.paymentModal = true;
            },

            async processPayment() {
                this.processing = true;
                const data = {
                    pelanggan_id: this.pelanggan_id,
                    metode_bayar: this.metode_bayar,
                    bayar: this.bayar,
                    items: this.cart.map(item => ({
                        produk_id: item.produk_id,
                        produk_satuan_id: item.produk_satuan_id,
                        qty: item.qty
                    })),
                    _token: '{{ csrf_token() }}'
                };

                try {
                    const response = await fetch('{{ route("pos.store") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    if (result.status === 'success') {
                        // Redirect to receipt
                        window.location.href = `{{ url('/pos/receipt') }}/${result.id}`;
                    } else {
                        alert('Gagal memproses transaksi: ' + (result.message || 'Error tidak diketahui'));
                    }
                } catch (e) {
                    alert('Terjadi kesalahan jaringan.');
                } finally {
                    this.processing = false;
                }
            }
        }
    }
</script>
@endsection
