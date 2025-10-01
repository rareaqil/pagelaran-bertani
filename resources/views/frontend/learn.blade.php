@extends('frontend.layouts.main')

@section('content')
    <div class="bg-white" x-data="{ filter: 'all', selected: null }">
        <!-- Hero -->
        <div class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-8 items-center">
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
            <div class="flex justify-end">
                <img src="https://plus.unsplash.com/premium_photo-1678344177250-bfdbed89fc03?q=80&w=987&auto=format&fit=crop"
                    alt="Kebun"
                    class="rounded-xl w-[300px] h-[500px] object-cover shadow-xl 
                   transition duration-300 transform hover:-translate-y-2 hover:shadow-2xl">
            </div>
        </div>

        <!-- Produk -->
        <div class="bg-green-600 py-12">
            <div class="max-w-7xl mx-auto px-6 md:px-20">
                <h3 class="text-center text-amber-500 text-3xl font-bold mb-8">
                    Jenis Jenis Buah
                </h3>

                <!-- Filter -->
                @if ($fruitTypes->isNotEmpty())
                    <div class="flex justify-center gap-4 mb-10">
                        <button @click="filter='all'"
                            :class="filter === 'all' ? 'bg-amber-500 text-white' : 'bg-white text-amber-500'"
                            class="px-4 py-2 rounded-lg shadow hover:scale-105 transition">
                            Semua
                        </button>
                        @foreach ($fruitTypes as $fruitType)
                            <button @click="filter='{{ strtolower($fruitType->name) }}'"
                                :class="filter === '{{ strtolower($fruitType->name) }}' ? 'bg-amber-500 text-white' :
                                    'bg-white text-amber-500'"
                                class="px-4 py-2 rounded-lg shadow hover:scale-105 transition">
                                {{ $fruitType->name }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Grid Produk -->
                <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">
                    @foreach ($posts as $post)
                        <div x-show="filter==='all' || filter==='{{ strtolower($post->fruitType->name ?? '') }}'"
                            class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden cursor-pointer"
                            @click='selected=@json($post)'>
                            <img src="{{ asset($post->image) }}" alt="{{ $post->name }}" class="w-full h-40 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold text-green-700">{{ $post->name }}</h4>
                                <p class="text-gray-600 text-sm mt-2">{!! Str::limit(strip_tags($post->intro), 80) !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Modal Detail Produk -->
        <div x-show="selected" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4" x-transition>
            <div
                class="bg-white rounded-2xl shadow-xl w-full max-w-lg relative overflow-hidden max-h-[80vh] overflow-y-auto">
                <!-- Tombol Close -->
                <button @click="selected=null"
                    class="absolute top-3 right-3 bg-gray-200 hover:bg-red-500 hover:text-white rounded-full w-8 h-8 flex items-center justify-center">
                    ✕
                </button>

                <!-- Gambar -->
                <img :src="selected.image" :alt="selected.name" class="w-full h-48 object-cover">

                <!-- Konten -->
                <div class="p-6">
                    <!-- Metadata -->
                    <div class="text-xs text-gray-500 mb-2 flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full"
                            x-text="selected.fruit_type ? selected.fruit_type.name : 'Umum'"></span>
                        <span>•</span>
                        <span x-text="selected.status === 'published' ? 'Dipublikasikan' : 'Draft'"></span>
                    </div>

                    <!-- Headline -->
                    <h2 class="text-2xl font-bold text-gray-800 leading-snug" x-text="selected.name"></h2>

                    <!-- Tanggal -->
                    <p class="mt-1 text-sm text-gray-500 italic"
                        x-text="dayjs(selected.published_at).format('D MMMM YYYY')"></p>

                    <!-- Isi berita -->
                    <div class="mt-4 text-gray-700 text-base leading-relaxed space-y-4" x-html="selected.content"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/id.js"></script>
    <script>
        dayjs.locale('id'); // bahasa Indonesia
    </script>

@endsection
