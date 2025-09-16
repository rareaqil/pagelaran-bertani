@extends('frontend.layouts.main')

@section('content')
    <!-- resources/views/produk.blade.php -->
    <div class="bg-white">
        <!-- Bagian Atas -->
        <div class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-8 items-center">
            <!-- Teks -->
            <div>
                <h2 class="text-xl md:text-6xl font-bold leading-snug">
                    <span class="text-green-600">Kenali</span>
                    <span class="text-amber-500"> Lebih Dekat</span><br>
                    <span class="text-amber-500">Produk Unggulan Dari</span><br>
                    <span class="text-green-600">Kebun Kami</span>
                </h2>
                <p class="mt-4 text-amber-500 text-base leading-relaxed">
                    Pagelaran Bertani menanam dengan teliti untuk menghadirkan melon dan jeruk segar, manis, dan
                    berkualitas.
                    Melon kami unggul dibanding pasaran dalam hal rasa, bentuk, dan kualitas.
                </p>
            </div>

            <!-- Gambar -->
            <div class="flex justify-end">
                <img src="https://plus.unsplash.com/premium_photo-1678344177250-bfdbed89fc03?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    alt="Kebun"
                    class="rounded-xl w-[300px] h-[500px] object-cover shadow-xl 
                   transition duration-300 transform hover:-translate-y-2 hover:shadow-2xl">
            </div>
        </div>
        <div class="bg-green-600 py-12">
            <div class="max-w-7xl mx-auto px-6 md:px-20">

                <!-- Judul -->
                <h3 class="text-center text-amber-500 text-3xl font-bold mb-8">
                    Jenis Jenis Buah
                </h3>

                <!-- Filter + Grid dalam satu scope Alpine -->
                <div x-data="{ filter: 'all' }">
                    <!-- Filter -->
                    <div class="flex justify-center gap-4 mb-10">
                        <button @click="filter='all'"
                            :class="filter === 'all' ? 'bg-amber-500 text-white' : 'bg-white text-amber-500'"
                            class="px-4 py-2 rounded-lg shadow hover:scale-105 transition">
                            Semua
                        </button>
                        <button @click="filter='melon'"
                            :class="filter === 'melon' ? 'bg-amber-500 text-white' : 'bg-white text-amber-500'"
                            class="px-4 py-2 rounded-lg shadow hover:scale-105 transition">
                            Melon
                        </button>
                        <button @click="filter='jeruk'"
                            :class="filter === 'jeruk' ? 'bg-amber-500 text-white' : 'bg-white text-amber-500'"
                            class="px-4 py-2 rounded-lg shadow hover:scale-105 transition">
                            Jeruk
                        </button>
                    </div>

                    <!-- Grid Card -->
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">
                        <!-- Card Melon 1 -->
                        <div x-show="filter==='all' || filter==='melon'"
                            class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1592928302818-9f620a4a3f3d?q=80&w=600&auto=format&fit=crop"
                                alt="Melon Hijau" class="w-full h-40 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold text-green-700">Melon Hijau</h4>
                                <p class="text-gray-600 text-sm mt-2">Segar dan manis, cocok dimakan langsung atau dijadikan
                                    jus.</p>
                            </div>
                        </div>

                        <!-- Card Jeruk -->
                        <div x-show="filter==='all' || filter==='jeruk'"
                            class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1615484477778-ef76aa1c67a4?q=80&w=600&auto=format&fit=crop"
                                alt="Jeruk Manis" class="w-full h-40 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold text-orange-500">Jeruk Manis</h4>
                                <p class="text-gray-600 text-sm mt-2">Rasa manis alami, sering digunakan untuk jus segar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
@endsection
