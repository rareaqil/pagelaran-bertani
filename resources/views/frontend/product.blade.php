@extends('frontend.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-10" x-data="{
        filter: 'all',
        sort: 'asc',
        selectedProduct: null,
        products: [
            { id: 1, name: 'Melon Inthanon', price: 50000, img: ['https://images.unsplash.com/photo-1592928302818-9f620a4a3f3d?q=80&w=600&auto=format&fit=crop', 'https://images.unsplash.com/photo-1587731480952-b6f9d3a12e7e?q=80&w=600&auto=format&fit=crop'], desc: 'Melon Inthanon segar dengan rasa manis alami dan tekstur lembut.', category: 'melon' },
            { id: 2, name: 'Melon Honey Globe', price: 50000, img: ['https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?q=80&w=600&auto=format&fit=crop'], desc: 'Honey Globe terkenal dengan rasa super manis dan daging buah tebal.', category: 'melon' },
            { id: 3, name: 'Jeruk Manis', price: 40000, img: ['https://images.unsplash.com/photo-1615484477778-ef76aa1c67a4?q=80&w=600&auto=format&fit=crop'], desc: 'Jeruk segar dengan rasa manis alami, cocok untuk jus.', category: 'jeruk' },
            { id: 4, name: 'Jeruk Peras', price: 35000, img: ['https://images.unsplash.com/photo-1601004890330-0e13b19e4f87?q=80&w=600&auto=format&fit=crop'], desc: 'Jeruk dengan rasa segar khas, pas dibuat es jeruk.', category: 'jeruk' },
            { id: 5, name: 'Melon Kuning', price: 52000, img: ['https://images.unsplash.com/photo-1615485290690-285a539321e6?q=80&w=600&auto=format&fit=crop'], desc: 'Melon kuning dengan daging buah renyah dan rasa menyegarkan.', category: 'melon' },
            { id: 6, name: 'Jeruk Bali', price: 60000, img: ['https://images.unsplash.com/photo-1606813902916-c79e4da63f71?q=80&w=600&auto=format&fit=crop'], desc: 'Jeruk bali besar dengan rasa manis segar dan kaya vitamin C.', category: 'jeruk' },
        ],
        filteredAndSorted() {
            let items = this.filter === 'all' ?
                this.products :
                this.products.filter(p => p.category === this.filter);
    
            return this.sort === 'asc' ?
                items.sort((a, b) => a.price - b.price) :
                items.sort((a, b) => b.price - a.price);
        }
    }">

        <!-- Header Filter & Sort -->
        <div class="flex justify-between items-center mb-8">
            <!-- Filter -->
            <div class="flex items-center gap-2">
                <span class="text-green-600 font-medium">Filter</span>
                <select x-model="filter" class="border rounded px-2 py-1 text-sm text-green-700 focus:ring-green-400">
                    <option value="all">Semua</option>
                    <option value="melon">Melon</option>
                    <option value="jeruk">Jeruk</option>
                </select>
            </div>

            <!-- Sort -->
            <div class="flex items-center gap-2">
                <span class="text-green-600 font-medium">Sort</span>
                <select x-model="sort" class="border rounded px-2 py-1 text-sm text-green-700 focus:ring-green-400">
                    <option value="asc">Harga Rendah</option>
                    <option value="desc">Harga Tinggi</option>
                </select>
            </div>
        </div>

        <!-- Grid Produk -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 overflow-y-auto max-h-[700px] pr-2">
            <template x-for="product in filteredAndSorted()" :key="product.id">
                <div @click="selectedProduct = product"
                    class="bg-orange-400 rounded-lg overflow-hidden flex flex-col hover:shadow-xl transition cursor-pointer">
                    <img :src="product.img[0]" :alt="product.name" class="h-48 w-full object-cover">
                    <div class="p-4 text-white flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-semibold text-lg" x-text="product.name"></h4>
                            <p class="text-sm" x-text="'Rp ' + product.price.toLocaleString('id-ID') + ' /Kg'"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Modal Detail Produk -->
        <div x-show="selectedProduct" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50" x-transition>
            <div class="bg-white rounded-xl max-w-lg w-full p-6 relative" @click.outside="selectedProduct = null">

                <!-- Tombol close -->
                <button class="absolute top-3 right-3 text-gray-500 hover:text-red-500"
                    @click="selectedProduct = null">✖</button>

                <!-- Slider Foto -->
                <div class="swiper mySwiper rounded-lg overflow-hidden mb-4">
                    <div class="swiper-wrapper">
                        <template x-for="img in selectedProduct.img" :key="img">
                            <div class="swiper-slide">
                                <img :src="img" class="w-full h-64 object-cover" />
                            </div>
                        </template>
                    </div>
                    <!-- Pagination & nav -->
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>

                <!-- Info Produk -->
                <h2 class="text-xl font-bold text-green-600" x-text="selectedProduct.name"></h2>
                <p class="text-gray-700 mt-2" x-text="selectedProduct.desc"></p>
                <p class="text-lg font-semibold text-amber-600 mt-3"
                    x-text="'Rp ' + selectedProduct.price.toLocaleString('id-ID') + ' /Kg'"></p>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- SwiperJS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => {
                if (Alpine.store('selectedProduct')) {
                    new Swiper('.mySwiper', {
                        loop: true,
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                    });
                }
            });
        });
    </script>
@endsection
