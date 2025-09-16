@extends('frontend.layouts.main')

@section('content')
    {{-- Hero Section --}}
    <section class="relative h-[500px] bg-cover bg-center flex items-center"
        style="background-image: url('https://plus.unsplash.com/premium_photo-1725902576834-522b1abe8d80?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">

        <div class="absolute inset-0 bg-black/30"></div>

        <div class="relative z-10 text-white px-10">
            <h1 class="text-6xl md:text-5xl font-bold">Eat Fresh, Live Healthy</h1>
            <p class="mt-2 text-xl">Dari Kebun Kami untuk Anda</p>
            <a href="/order"
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
        <div class="w-full h-[350px]">
            <img src="https://plus.unsplash.com/premium_photo-1700089174974-871b8ea3731d?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Melon Segar" class="w-full h-full object-cover rounded-lg shadow">
        </div>
    </section>

    {{-- Product Section --}}
    <section class="py-16 px-6 md:px-20 bg-green-600">
        <h2 class="text-xl md:text-2xl font-bold text-amber-500 mb-8">Belanja Buah Segar Musim Ini</h2>

        <div class="grid md:grid-cols-4 gap-8">
            <!-- Product Card -->
            <div
                class="bg-white rounded-lg shadow-md hover:shadow-2xl transition transform hover:-translate-y-2 hover:scale-105 p-4 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1615485290690-285a539321e6?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    alt="Melon Inthanon" class="w-full h-48 object-cover rounded-md">
                <h3 class="mt-4 text-lg font-semibold text-gray-800">Melon Inthanon</h3>
                <p class="text-gray-600">Rp 50.000/kg</p>
            </div>

            <div
                class="bg-white rounded-lg shadow-md hover:shadow-2xl transition transform hover:-translate-y-2 hover:scale-105 p-4 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1571575173700-afb9492e6a50?q=80&w=1036&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    alt="Melon Honey Globe" class="w-full h-48 object-cover rounded-md">
                <h3 class="mt-4 text-lg font-semibold text-gray-800">Melon Honey Globe</h3>
                <p class="text-gray-600">Rp 50.000/kg</p>
            </div>

            <div
                class="bg-white rounded-lg shadow-md hover:shadow-2xl transition transform hover:-translate-y-2 hover:scale-105 p-4 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1571575173700-afb9492e6a50?q=80&w=1036&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    alt="Melon Honey Globe" class="w-full h-48 object-cover rounded-md">
                <h3 class="mt-4 text-lg font-semibold text-gray-800">Melon Honey Globe</h3>
                <p class="text-gray-600">Rp 50.000/kg</p>
            </div>

            <div
                class="bg-white rounded-lg shadow-md hover:shadow-2xl transition transform hover:-translate-y-2 hover:scale-105 p-4 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1615485290690-285a539321e6?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    alt="Melon Honey Globe" class="w-full h-48 object-cover rounded-md">
                <h3 class="mt-4 text-lg font-semibold text-gray-800">Melon Honey Globe</h3>
                <p class="text-gray-600">Rp 50.000/kg</p>
            </div>
        </div>

        <!-- Button -->
        <div class="mt-10 text-center">
            <a href="/order"
                class="bg-amber-500 text-white px-6 py-2 rounded shadow hover:bg-amber-600 hover:scale-105 transform transition duration-200 inline-block">
                Order Online
            </a>
        </div>
    </section>

    {{-- Testimoni Section --}}
    <section class="py-16 px-6 md:px-20 bg-white">
        <h2 class="text-xl md:text-2xl font-bold text-amber-500 mb-8">Testimoni Pelanggan</h2>

        <div class="grid md:grid-cols-4 gap-8">
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                <img src="/images/testi1.jpg" alt="Testimoni" class="w-full h-48 object-cover">
                <div class="bg-green-600 p-4 text-white">
                    <p class="font-semibold">Andi</p>
                    <p class="text-xs">12 Sep 2025</p>
                    <p class="mt-2 text-sm">Buah segar banget, pengiriman cepat!</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                <img src="/images/testi2.jpg" alt="Testimoni" class="w-full h-48 object-cover">
                <div class="bg-green-600 p-4 text-white">
                    <p class="font-semibold">Sinta</p>
                    <p class="text-xs">10 Sep 2025</p>
                    <p class="mt-2 text-sm">Rasanya manis dan fresh, recommended!</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                <img src="/images/testi3.jpg" alt="Testimoni" class="w-full h-48 object-cover">
                <div class="bg-green-600 p-4 text-white">
                    <p class="font-semibold">Budi</p>
                    <p class="text-xs">05 Sep 2025</p>
                    <p class="mt-2 text-sm">Harga oke, kualitas top.</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                <img src="/images/testi4.jpg" alt="Testimoni" class="w-full h-48 object-cover">
                <div class="bg-green-600 p-4 text-white">
                    <p class="font-semibold">Rina</p>
                    <p class="text-xs">01 Sep 2025</p>
                    <p class="mt-2 text-sm">Puas banget belanja di sini.</p>
                </div>
            </div>
        </div>

        <!-- Button -->
        <div class="mt-10 text-center">
            <a href="/testimonials"
                class="bg-amber-500 text-white px-6 py-2 rounded shadow hover:bg-amber-600 hover:scale-105 transform transition duration-200 inline-block">
                See More
            </a>
        </div>
    </section>

    {{-- Footer Section --}}
    <footer class="bg-green-600 text-white p-4">
        <!-- Kotak Orange -->
        <div class="bg-amber-500 text-white rounded-lg mx-6 md:mx-20 mt-10 p-8 grid md:grid-cols-3 text-center">
            <!-- Address -->
            <div class="md:border-r md:border-white/50 md:pr-6 mb-6 md:mb-0">
                <p>500 Terry Francine Street</p>
                <p>San Francisco, CA 94158</p>
                <p><a href="mailto:info@my-domain.com" class="underline">info@my-domain.com</a></p>
                <p>Tel: 123-456-7890</p>
                <p>Fax: 123-456-7890</p>
            </div>

            <!-- Operating Hours -->
            <div class="md:border-r md:border-white/50 md:px-6 mb-6 md:mb-0">
                <h3 class="font-bold mb-2">Operating Hours</h3>
                <p>Mon – Fri: 8am – 8pm</p>
                <p>Saturday: 9am – 7pm</p>
                <p>Sunday: 9am – 8pm</p>
            </div>

            <!-- Delivery Hours -->
            <div class="md:pl-6">
                <h3 class="font-bold mb-2">Delivery Hours</h3>
                <p>Mondays: 8am – 1pm</p>
                <p>Wednesdays: 8am – 1pm</p>
                <p>Fridays: 8am – 1pm</p>
            </div>
        </div>

        <!-- Map -->
        <div class="w-full h-64 mt-6 rounded-lg overflow-hidden">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15865.14473374119!2d106.812312577124!3d-6.225947325061326!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f14d30079f01%3A0x2e74f2341fff266d!2sStadion%20Utama%20Gelora%20Bung%20Karno!5e0!3m2!1sid!2sid!4v1757780431002!5m2!1sid!2sid"
                class="w-full h-full border-0" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </footer>
@endsection
