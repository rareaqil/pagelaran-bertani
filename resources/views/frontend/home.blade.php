@extends('frontend.layouts.main')

@section('content')
    {{-- Hero Section --}}
    <section class="relative h-[500px] bg-cover bg-center flex items-center"
        style="background-image: url('{{ asset('media/Photo_Melon/GH1/Honey globe 47.jpg') }}');">

        <div class="absolute inset-0 bg-black/30"></div>

        <div class="relative z-10 text-white px-10">
            <h1 class="text-6xl md:text-5xl font-bold">Eat Fresh, Live Healthy</h1>
            <p class="mt-2 text-xl">Dari Kebun Kami untuk Anda</p>
            <a href="/order-product"
                class="mt-4 bg-amber-500 text-white px-6 py-2 rounded shadow hover:bg-amber-600 hover:scale-105 transform transition duration-200 inline-block">
                Order Online
            </a>
        </div>
    </section>

    {{-- About Section --}}
    <section class="py-16 px-6 md:px-20 grid md:grid-cols-3 gap-10 items-center">
        <!-- Kiri (teks) -->
        <div class="md:col-span-2">
            <h2 class="text-2xl font-bold text-amber-500 uppercase">Pagelaran Bertani</h2>
            <p class="mt-4 w-[600px] text-green-700 leading-relaxed">
                Pagelaran Bertani menghadirkan agribisnis berbasis kualitas dengan fokus pada budidaya dan penjualan
                buah segar. Kami menyediakan melon premium (Inthanon, Honey Globe, dan 2 varietas unggul lainnya)
                serta jeruk pilihan (Siem Madu dan Siem Keprok) yang dikenal dengan rasa manis, bentuk sempurna, dan
                kualitas lebih baik dibandingkan pasaran.
            </p>
            <a href="/learn"
                class="mt-6 inline-block bg-amber-500 text-white px-5 py-2 rounded shadow hover:bg-amber-600 transition">
                Learn More
            </a>
        </div>

        <!-- Kanan (gambar) -->
        <div class="w-full h-[350px] [perspective:1000px]">
            <div
                class="relative w-full h-full transition-transform duration-500 transform group-hover:rotate-y-6 group-hover:-rotate-x-3 group-hover:scale-105 group-hover:shadow-2xl rounded-lg group">
                <img src="{{ asset('media/Photo_Melon/GH2/Inthanon Jelang Panen.jpg') }}" alt="Melon Segar"
                    class="w-full h-full object-cover rounded-lg shadow-lg transition duration-300 transform hover:-translate-y-2 hover:shadow-2xl">
            </div>
        </div>
    </section>

    {{-- Product Section --}}
    <section class="py-16 px-6 md:px-20 bg-green-600" x-data="{ openModal: false, product: {} }">
        <h2 class="text-xl md:text-2xl font-bold text-amber-500 mb-8">Belanja Buah Segar Musim Ini</h2>

        <!-- Grid Product -->
        <div class="grid md:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div @click="openModal = true; product = {
                    name: '{{ $product->name }}',
                    description: '{{ $product->description }}',
                    price: '{{ number_format($product->price, 0, ',', '.') }}',
                    image: '{{ asset('storage/' . str_replace(' ', '%20', $product->image)) }}',
                    weight: '{{ $product->weight }}',
                    sku: '{{ $product->sku }}',
                    stock: '{{ $product->stock }}'
                }"
                    class="bg-white rounded-lg shadow-md hover:shadow-2xl transition transform hover:-translate-y-2 hover:scale-105 p-4 cursor-pointer">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
                        class="w-full h-48 object-cover rounded-md">
                    <h3 class="mt-4 text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                    <p class="text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}/kg</p>
                </div>
            @empty
                <p class="text-white">Belum ada produk tersedia.</p>
            @endforelse
        </div>

        <div class="mt-10 text-center">
            <a href="/order-product"
                class="bg-amber-500 text-white px-6 py-2 rounded shadow hover:bg-amber-600 hover:scale-105 transform transition duration-200 inline-block">
                Order Online
            </a>
        </div>

        <!-- Modal Product (di luar grid, full screen) -->
        <div x-show="openModal" x-transition x-cloak
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 rounded-lg shadow-lg relative p-6">
                <!-- Close -->
                <button @click="openModal = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                    ✕
                </button>

                <!-- Isi Modal -->
                <div class="grid md:grid-cols-2 gap-6">
                    <img src="{{ $product->image ?? '' }}" alt="{{ $product->name ?? '' }}"
                        class="w-full h-64 object-cover">
                    <div>
                        <h3 class="text-2xl font-bold text-green-700" x-text="product.name"></h3>
                        <p class="text-gray-600 mt-2" x-text="'SKU: ' + product.sku"></p>
                        <p class="text-gray-600" x-text="'Stock: ' + product.stock"></p>
                        <p class="mt-2 text-lg font-semibold text-amber-600" x-text="'Rp ' + product.price + '/kg'"></p>
                        <p class="mt-4 text-gray-700 leading-relaxed" x-text="product.description"></p>
                        <p class="mt-2 text-sm text-gray-500" x-text="'Berat: ' + product.weight + ' kg'"></p>

                        <a href="/order-product"
                            class="mt-6 inline-block bg-amber-500 text-white px-5 py-2 rounded shadow hover:bg-amber-600 transition">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimoni Section --}}
    <section class="py-16 px-6 md:px-20 bg-white" x-data="{ openModal: false }">
        <h2 class="text-xl md:text-2xl font-bold text-amber-500 mb-8">Testimoni Pelanggan</h2>

        @if ($testimonials->isNotEmpty())
            <!-- Grid testimoni singkat -->
            <div class="grid md:grid-cols-4 gap-8">
                @foreach ($testimonials->take(4) as $testimonial)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                        <img src="{{ $testimonial->product?->image ? asset($testimonial->product->image) : asset('/images/default.jpg') }}"
                            alt="{{ $testimonial->product?->name ?? 'Produk Tidak Dikenal' }}"
                            class="w-full h-48 object-cover">

                        <div class="bg-green-600 p-4 text-white">
                            <p class="font-semibold">{{ $testimonial->user?->name ?? 'Anonim' }}</p>
                            <p class="text-xs">
                                {{ optional($testimonial->created_at)->format('d M Y') ?? 'Tanggal tidak diketahui' }}
                            </p>
                            <p class="mt-2 text-sm">
                                {{ $testimonial->comment ?: 'Belum ada komentar yang diberikan.' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Button -->
            @if ($testimonials->count() > 4)
                <div class="mt-10 text-center">
                    <button @click="openModal = true"
                        class="bg-amber-500 text-white px-6 py-2 rounded shadow hover:bg-amber-600 hover:scale-105 transform transition duration-200 inline-block">
                        See More
                    </button>
                </div>
            @endif
        @else
            <h1 class="text-lg text-center text-gray-500">Belum ada testimoni dari pelanggan.</h1>
        @endif

        <!-- Modal -->
        <div x-show="openModal" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
            x-cloak>
            <div
                class="bg-white w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 rounded-lg shadow-lg overflow-y-auto max-h-[80vh] relative">
                <!-- Close button -->
                <button @click="openModal = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                    ✕
                </button>

                <div class="p-6">
                    <h3 class="text-lg font-bold text-amber-500 mb-4">Semua Testimoni</h3>
                    @if ($testimonials->isNotEmpty())
                        <div class="grid md:grid-cols-2 gap-6">
                            @foreach ($testimonials as $testimonial)
                                <div class="bg-green-600 p-4 text-white rounded-lg">
                                    <p class="font-semibold">{{ $testimonial->user?->name ?? 'Anonim' }}</p>
                                    <p class="text-xs">
                                        {{ optional($testimonial->created_at)->format('d M Y') ?? 'Tanggal tidak diketahui' }}
                                    </p>
                                    <p class="mt-2 text-sm">
                                        {{ $testimonial->comment ?: 'Belum ada komentar yang diberikan.' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-600">Belum ada testimoni untuk ditampilkan.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Footer Section --}}
    <footer class="bg-green-600 text-white p-4">
        <!-- Kotak Orange -->
        <div
            class="bg-amber-500 text-white rounded-lg mx-6 md:mx-20 mt-10 p-8 grid md:grid-cols-3 text-center md:text-left divide-y md:divide-y-0 md:divide-x divide-white/40">
            <!-- Lokasi -->
            <div class="md:pr-6 pb-6 md:pb-0">
                <h3 class="font-bold text-lg mb-3 border-b border-white/50 inline-block pb-1">📍 Lokasi</h3>
                <p class="leading-relaxed mt-2">
                    Jl. Imam Bonjol Desa Kademangan, Kec. Gondanglegi, Kab. Malang
                </p>
                <p class="mt-2 text-sm opacity-90 leading-relaxed">
                    <span class="font-semibold">Kebun 1:</span> QJR4+47 Kademangan, Kabupaten Malang, Jawa Timur<br>
                    <span class="font-semibold">Kebun 2:</span> QJR6+38H, Krajan, Suwaru, Kec. Pagelaran, Kabupaten Malang,
                    Jawa Timur 65174
                </p>
                <p class="mt-3 text-sm">
                    ✉️ <a href="mailto:{{ $email ?? '-' }}" class="underline hover:text-amber-100 transition">
                        {{ $email ?? '-' }}
                    </a>
                </p>
            </div>

            <!-- Jam Operasional -->
            <div class="md:px-6 py-6 md:py-0">
                <h3 class="font-bold text-lg mb-3 border-b border-white/50 inline-block pb-1">⏰ Jam Operasional</h3>
                <ul class="space-y-1 text-sm opacity-90">
                    <li><span class="font-medium">Minggu:</span> 09.00 – 17.00</li>
                    <li><span class="font-medium">Senin:</span> Tutup</li>
                    <li><span class="font-medium">Selasa:</span> 09.00 – 17.00</li>
                    <li><span class="font-medium">Rabu:</span> Tutup</li>
                    <li><span class="font-medium">Kamis:</span> Tutup</li>
                    <li><span class="font-medium">Jumat:</span> Tutup</li>
                    <li><span class="font-medium">Sabtu:</span> 15.00 – 17.00</li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="md:pl-6 pt-6 md:pt-0">
                <h3 class="font-bold text-lg mb-3 border-b border-white/50 inline-block pb-1">📞 Contact Person</h3>
                <ul class="space-y-2 text-sm opacity-90">
                    <li>
                        Handono —
                        <a href="https://wa.me/6282186641386" target="_blank"
                            class="underline hover:text-amber-100 transition">
                            0821-8664-1386
                        </a>
                    </li>
                    <li>
                        Bagus —
                        <a href="https://wa.me/6281231034468" target="_blank"
                            class="underline hover:text-amber-100 transition">
                            0812-3103-4468
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Map -->
        <div class="w-full h-64 mt-6 rounded-lg overflow-hidden">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3948.9285127041194!2d112.60165637538918!3d-8.209944282373504!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78a16a6f8767d9%3A0x905e14d7bbce8892!2sPAGELARAN%20Bertani!5e0!3m2!1sid!2sid!4v1760202867375!5m2!1sid!2sid"
                class="w-full h-full border-0" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </footer>
@endsection
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
