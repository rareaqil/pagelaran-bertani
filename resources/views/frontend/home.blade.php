@extends('frontend.layouts.main')

@section('content')
    {{-- Hero Section --}}
    <section class="relative h-[500px] bg-cover bg-center flex items-center"
        style="background-image: url('https://plus.unsplash.com/premium_photo-1725902576834-522b1abe8d80?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">

        <div class="absolute inset-0 bg-black/30"></div>

        <div class="relative z-10 text-white px-10">
            <h1 class="text-4xl md:text-5xl font-bold">Eat Fresh, Live Healthy</h1>
            <p class="mt-2 text-lg">Dari Kebun Kami untuk Anda</p>
            <a href="/order"
                class="mt-4 inline-block bg-orange-500 text-white px-6 py-2 rounded shadow hover:bg-orange-600 transition">
                Order Online
            </a>
        </div>
    </section>

    {{-- About Section --}}
    <section class="py-16 px-6 md:px-20 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-2xl font-bold text-orange-500 uppercase">Pagelaran Bertani</h2>
            <p class="mt-4 text-gray-700 leading-relaxed">
                Pagelaran Bertani menghadirkan agribisnis berbasis kualitas dengan fokus pada budidaya dan penjualan
                buah segar. Kami menyediakan melon premium (Inthanon, Honey Globe, dan 2 varietas unggul lainnya)
                serta jeruk pilihan (Siem Madu dan Siem Keprok) yang dikenal dengan rasa manis, bentuk sempurna, dan
                kualitas lebih baik dibandingkan pasaran.
            </p>
            <a href="/learn"
                class="mt-6 inline-block bg-orange-500 text-white px-5 py-2 rounded shadow hover:bg-orange-600 transition">
                Learn More
            </a>
        </div>

        <div>
            <img src="https://images.unsplash.com/photo-1605025189223-3d70f4e0a1d6?q=80&w=1000&auto=format&fit=crop"
                alt="Melon Segar" class="rounded-lg shadow">
        </div>
    </section>
@endsection
