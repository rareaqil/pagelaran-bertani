@extends('frontend.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-10" x-data="{
        filter: 'all',
        sort: 'asc',
        selectedProduct: null,
        products: @js($products),
        filteredAndSorted() {
            let items = this.filter === 'all' ?
                this.products :
                this.products.filter(p =>
                    p.fruit_type_id &&
                    (p.fruit_type?.name || '').toLowerCase() === this.filter
                );
    
            return this.sort === 'asc' ?
                items.sort((a, b) => a.price - b.price) :
                items.sort((a, b) => b.price - a.price);
        }
    }">

        <!-- Header Filter & Sort -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-2">
                <span class="text-green-600 font-medium">Filter</span>
                <select x-model="filter" class="border rounded px-2 py-1 text-sm text-green-700 focus:ring-green-400">
                    <option value="all">Semua</option>
                    <option value="melon">Melon</option>
                    <option value="jeruk">Jeruk</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-green-600 font-medium">Sort</span>
                <select x-model="sort" class="border rounded px-2 py-1 text-sm text-green-700 focus:ring-green-400">
                    <option value="asc">Harga Rendah</option>
                    <option value="desc">Harga Tinggi</option>
                </select>
            </div>
        </div>

        <!-- Grid Produk (pakai Alpine x-for, bukan Blade foreach) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 overflow-y-auto max-h-[700px] pr-2">
            <template x-for="product in filteredAndSorted()" :key="product.id">
                <div class="bg-orange-400 rounded-lg overflow-hidden flex flex-col hover:shadow-xl transition"
                    @click="selectedProduct = product">

                    <!-- Gambar -->
                    <img :src="product.image" :alt="product.name" class="h-48 w-full object-cover">

                    <!-- Konten Card -->
                    <div class="p-4 text-white flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-semibold text-lg" x-text="product.name"></h4>
                            <p class="text-sm">Rp <span x-text="Number(product.price).toLocaleString('id-ID')"></span> /Kg
                            </p>
                        </div>

                        <!-- Form Qty + Add -->
                        <!-- Form Qty + Add -->
                        <div class="mt-3 flex items-center gap-2" @click.stop>
                            @auth
                                <button type="button" @click="changeQty(product.id, -1)"
                                    class="rounded bg-gray-200 text-black px-2 py-1">-</button>
                                <input type="number" :id="`qty-${product.id}`" value="1" min="1"
                                    class="w-12 rounded border text-center text-black" readonly />
                                <button type="button" @click="changeQty(product.id, 1)"
                                    class="rounded bg-gray-200 text-black px-2 py-1">+</button>
                                <button type="button" @click="addToCart(product.id)"
                                    class="rounded bg-blue-600 px-3 py-1 text-white">Add</button>
                                {{-- Info stok --}}
                                <span class="text-sm text-gray-100 ml-2">
                                    Stok: <span x-text="product.stock"></span>
                                </span>
                            @endauth

                            @guest
                                <button disabled
                                    class="rounded bg-gray-300 text-gray-500 px-2 py-1 cursor-not-allowed">-</button>
                                <input type="number" value="0" disabled
                                    class="w-12 rounded border text-center text-gray-400 bg-gray-100" />
                                <button disabled
                                    class="rounded bg-gray-300 text-gray-500 px-2 py-1 cursor-not-allowed">+</button>
                                <button disabled class="rounded bg-gray-400 px-3 py-1 text-white cursor-not-allowed">Login
                                    dulu</button>
                            @endguest
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Modal -->
        <div x-show="selectedProduct" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50" x-transition>
            <div class="bg-white rounded-xl max-w-lg w-full p-6 relative" @click.outside="selectedProduct = null">
                <button class="absolute top-3 right-3 text-gray-500 hover:text-red-500"
                    @click="selectedProduct = null">✖</button>

                <!-- Foto Produk -->
                <img :src="selectedProduct.image" class="w-full h-64 object-cover rounded mb-4">

                <!-- Info Produk -->
                <h2 class="text-xl font-bold text-green-600" x-text="selectedProduct.name"></h2>
                <p class="text-gray-700 mt-2" x-text="selectedProduct.description"></p>

                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <p><span class="font-semibold">Harga:</span>
                        <span x-text="'Rp ' + Number(selectedProduct.price).toLocaleString('id-ID')"></span>
                    </p>
                    <p><span class="font-semibold">Stok:</span>
                        <span x-text="selectedProduct.stock"></span>
                    </p>
                    <p><span class="font-semibold">Berat:</span>
                        <span x-text="selectedProduct.weight + ' kg'"></span>
                    </p>
                    <p><span class="font-semibold">SKU:</span>
                        <span x-text="selectedProduct.sku"></span>
                    </p>
                    <p><span class="font-semibold">Jenis Buah:</span>
                        <span x-text="selectedProduct.fruit_type?.name ?? '-'"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" class="fixed top-20 right-4 z-50 space-y-2 w-72">
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        // Ubah jumlah qty
        window.changeQty = function(productId, delta) {
            const input = document.getElementById(`qty-${productId}`);
            let val = parseInt(input.value) + delta;
            if (val < 1) val = 1;
            input.value = val;
        };

        // Toast notifikasi
        function showToast(message, type = "success") {
            const container = document.getElementById("toast-container");
            const toast = document.createElement("div");
            toast.className = `px-4 py-2 rounded-lg shadow-lg text-white ${
            type === "success" ? "bg-green-600" : "bg-red-600"
        }`;
            toast.innerText = message;
            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add("opacity-0", "transition", "duration-500");
                setTimeout(() => {
                    toast.remove();
                    location.reload();
                }, 500);
            }, 3000);
        }

        // Tambah ke keranjang
        window.addToCart = function(productId) {
            const qty = parseInt(document.getElementById(`qty-${productId}`).value) || 1;
            fetch("{{ route('cart.add') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        id: productId,
                        quantity: qty
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        // Update badge cart
                        if (document.getElementById("cart-count")) {
                            document.getElementById("cart-count").innerText = res.cartCount;
                        }
                        showToast("Berhasil menambahkan ke keranjang!", "success");
                        // location.reload();
                    } else {
                        showToast(res.message || "Gagal menambahkan item", "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast("Terjadi kesalahan saat menambahkan ke keranjang", "error");
                });
        };
    </script>
@endsection
