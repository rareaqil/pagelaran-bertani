@extends('frontend.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-10" x-data="{
        filter: 'all',
        sort: 'asc',
        selectedProduct: null,
        products: @js($products),
        openModal(product) {
            this.selectedProduct = product;
        },
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

        <!-- Grid Produk -->
        <div
            class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 overflow-y-auto max-h-[80vh] pr-1 sm:pr-2">
            <template x-for="product in filteredAndSorted()" :key="product.id">
                <div class="bg-orange-400 rounded-lg overflow-hidden flex flex-col hover:shadow-xl transition transform hover:-translate-y-1 hover:scale-[1.02] cursor-pointer"
                    @click="selectedProduct = product">
                    <!-- Gambar -->
                    <img :src="(product.image && product.image.includes(',')) ?
                    product.image.split(',')[0].trim(): product.image"
                        :alt="product.name" class="h-36 sm:h-44 md:h-48 w-full object-cover" loading="lazy">

                    <!-- Konten Card -->
                    <div class="p-3 sm:p-4 text-white flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-semibold text-base sm:text-lg truncate" x-text="product.name"></h4>
                            <p class="text-xs sm:text-sm mt-1">
                                Rp <span x-text="Number(product.price).toLocaleString('id-ID')"></span> /Buah
                            </p>
                        </div>

                        <!-- Form Qty + Add -->
                        <div class="mt-3 flex flex-wrap items-center gap-2" @click.stop>
                            @auth
                                <!-- Tombol Quantity -->
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="changeQty(product.id, -1)"
                                        class="rounded bg-gray-200 text-black px-2 py-1 text-sm sm:text-base">−</button>
                                    <input type="number" :id="`qty-${product.id}`" value="1" min="1"
                                        class="w-10 sm:w-12 rounded border text-center text-black text-sm sm:text-base"
                                        readonly />
                                    <button type="button" @click="changeQty(product.id, 1)"
                                        class="rounded bg-gray-200 text-black px-2 py-1 text-sm sm:text-base">+</button>
                                </div>

                                <!-- Tombol Add -->
                                <button type="button" @click="addToCart(product.id)"
                                    class="rounded bg-blue-600 px-3 sm:px-4 py-1 text-white text-sm sm:text-base font-medium">
                                    Add
                                </button>

                                <span class="text-xs sm:text-sm text-gray-100 ml-auto block sm:inline">
                                    Stok: <span x-text="product.stock"></span>
                                </span>
                            @endauth

                            @guest
                                <button disabled
                                    class="rounded bg-gray-300 text-gray-500 px-2 py-1 text-xs sm:text-sm cursor-not-allowed">−</button>
                                <input type="number" value="0" disabled
                                    class="w-10 sm:w-12 rounded border text-center text-gray-400 bg-gray-100 text-xs sm:text-sm" />
                                <button disabled
                                    class="rounded bg-gray-300 text-gray-500 px-2 py-1 text-xs sm:text-sm cursor-not-allowed">+</button>
                                <button disabled
                                    class="rounded bg-gray-400 px-3 sm:px-4 py-1 text-white cursor-not-allowed text-xs sm:text-sm">
                                    Login dulu
                                </button>
                            @endguest
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Modal Produk -->
        <div x-show="selectedProduct"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 px-4 sm:px-6 md:px-0" x-transition>
            <div class="bg-white rounded-2xl w-full max-w-lg md:max-w-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]"
                @click.outside="selectedProduct = null" x-data="{
                    imgs: [],
                    currentImage: '',
                    init() {
                        this.$watch('selectedProduct', (val) => {
                            if (val && val.image) {
                                this.imgs = val.image.split(',').map(i => i.trim());
                                this.currentImage = this.imgs[0];
                            }
                        });
                    }
                }">
                <!-- Close Button -->
                <button class="absolute top-3 right-3 text-gray-500 hover:text-red-500 transition"
                    @click="selectedProduct = null" aria-label="Tutup modal">
                    ✖
                </button>

                <!-- Gallery -->
                <template x-if="selectedProduct && currentImage">
                    <div>
                        <!-- Main Image -->
                        <img :src="currentImage"
                            class="w-full max-h-[300px] sm:max-h-[400px] object-cover rounded-xl mb-4 shadow-md">

                        <!-- Thumbnail Row -->
                        <div class="flex gap-2 mt-3 overflow-x-auto pb-2">
                            <template x-for="(img, idx) in imgs" :key="idx">
                                <img :src="img"
                                    class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg cursor-pointer border-2 flex-shrink-0 transition"
                                    :class="currentImage === img ?
                                        'border-amber-500 ring-2 ring-amber-300' :
                                        'border-transparent hover:border-amber-400'"
                                    @click="currentImage = img">
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Product Info -->
                <div class="mt-5">
                    <h2 class="text-xl sm:text-2xl font-bold text-green-600 text-center sm:text-left"
                        x-text="selectedProduct?.name"></h2>

                    <p class="text-gray-700 mt-2 text-sm sm:text-base leading-relaxed"
                        x-text="selectedProduct?.description"></p>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm sm:text-base">
                        <p><span class="font-semibold">Harga:</span>
                            <span x-text="'Rp ' + Number(selectedProduct?.price).toLocaleString('id-ID')"></span>
                        </p>
                        <p><span class="font-semibold">Stok:</span>
                            <span x-text="selectedProduct?.stock"></span>
                        </p>
                        <p><span class="font-semibold">Berat:</span>
                            <span x-text="selectedProduct?.weight ? selectedProduct.weight + ' kg' : '-'"></span>
                        </p>
                        <p><span class="font-semibold">SKU:</span>
                            <span x-text="selectedProduct?.sku ?? '-'"></span>
                        </p>
                        <p class="sm:col-span-2"><span class="font-semibold">Jenis Buah:</span>
                            <span x-text="selectedProduct?.fruit_type?.name ?? '-'"></span>
                        </p>
                    </div>
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
