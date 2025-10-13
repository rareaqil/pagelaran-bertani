@extends('frontend.layouts.main')

@section('content')
    <div class="bg-white" x-data="{ filter: 'all', selected: null }">
        <!-- Hero -->
        <div class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-8 items-center">
            <!-- Teks -->
            <div class="text-center md:text-left">
                <h2 class="text-3xl sm:text-4xl md:text-6xl font-bold leading-snug">
                    <span class="text-green-600">Kenali</span>
                    <span class="text-amber-500"> Lebih Dekat</span><br class="hidden sm:block">
                    <span class="text-amber-500">Produk Unggulan Dari</span><br class="hidden sm:block">
                    <span class="text-green-600">Kebun Kami</span>
                </h2>
                <p class="mt-4 text-amber-500 text-sm sm:text-base leading-relaxed max-w-md mx-auto md:mx-0">
                    Pagelaran Bertani menanam dengan teliti untuk menghadirkan melon dan jeruk segar, manis, dan
                    berkualitas.
                    Melon kami unggul dibanding pasaran dalam hal rasa, bentuk, dan kualitas.
                </p>
            </div>

            <!-- Gambar -->
            <div class="flex justify-center md:justify-end">
                <img src="{{ asset('media/Photo_Melon/GH2/Inthanon Grade A-B-C.jpg') }}" alt="Kebun"
                    class="rounded-xl w-[250px] sm:w-[300px] md:w-[400px] h-[350px] sm:h-[450px] md:h-[500px] object-cover shadow-xl
                   transition duration-300 transform hover:-translate-y-2 hover:shadow-2xl">
            </div>
        </div>

        <!-- Tentang Kami -->
        <div class="bg-white py-16">
            <div class="max-w-6xl mx-auto px-6 md:px-12">
                <!-- Judul -->
                <h2 class="text-4xl font-extrabold text-center text-green-700 mb-6">
                    Tentang Kami
                </h2>
                <p class="text-center text-gray-600 max-w-3xl mx-auto mb-12">
                    Dari desa sejuk di Malang, kami menanam bukan hanya buah, tetapi juga harapan dan masa depan pertanian
                    Indonesia.
                </p>

                <!-- Bagian Deskripsi -->
                <div class="space-y-8 text-gray-700 leading-relaxed">
                    <p>
                        <span class="font-semibold text-green-700">CV Pagelaran Bertani</span> lahir dari semangat gotong
                        royong dan cita-cita besar untuk membangun pertanian berkelanjutan di Indonesia.
                        Berdiri sejak <span class="font-semibold text-amber-600">tahun 2023</span>, dua kakak beradik yang
                        tumbuh di desa berhawa sejuk di Malang, Jawa Timur,
                        berkolaborasi membangun usaha pertanian yang tidak sekadar berorientasi pada hasil, tetapi juga pada
                        <span class="italic">nilai, keberlanjutan, dan kesejahteraan bersama.</span>
                    </p>

                    <div class="border-l-4 border-amber-500 pl-4 italic text-gray-600">
                        “Pertanian bagi kami bukan hanya tentang tanam dan panen, tetapi tentang membangun ekosistem yang
                        sehat, kompetitif, dan terpercaya.”
                    </div>

                    <p>
                        Kami memilih <span class="font-semibold text-amber-600">melon premium</span> sebagai fokus utama
                        bukan karena tren,
                        melainkan karena maknanya yang mendalam. Melon bagi kami adalah simbol keseimbangan—antara sains dan
                        seni bercocok tanam,
                        antara kerja keras dan hasil manis yang dinikmati bersama.
                        Melalui praktik <span class="font-semibold">Good Agricultural Practices</span> di sistem <span
                            class="italic">screen house</span>,
                        kami menjaga kualitas buah sekaligus keberlanjutan lingkungan.
                    </p>

                    <p>
                        Dengan semangat gotong royong, kami berkomitmen menciptakan rantai pasok buah premium yang memberi
                        manfaat bagi semua pihak—petani, konsumen, dan mitra usaha.
                        Visi kami adalah menjadi penyedia buah segar unggulan yang tumbuh dari desa, untuk kemajuan
                        pertanian nasional.
                    </p>
                </div>

                <!-- Misi -->
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-green-700 mb-4">Visi & Misi Kami</h3>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>Menerapkan praktik budidaya yang baik dan berkelanjutan.</li>
                        <li>Menghadirkan produk melon premium berkualitas tinggi seperti <span
                                class="font-semibold">Inthanon, Honey Globe, The Blues</span>, serta inovasi baru yaitu
                            <span class="italic">Melon Premium Typical Negeri Ginseng</span>.
                        </li>
                        <li>Mendorong kesejahteraan petani lokal melalui kolaborasi dan pemberdayaan.</li>
                        <li>Menjadi bagian dari gerakan pertanian yang lebih hijau, sehat, dan berdaya saing.</li>
                    </ul>
                </div>

                <!-- Profil Pendiri -->
                <div class="mt-16">
                    <h3 class="text-2xl font-bold text-green-700 mb-8 text-center">Profil Pendiri</h3>
                    <div class="grid md:grid-cols-2 gap-10">
                        <!-- Founder -->
                        <div class="bg-gray-50 p-6 rounded-xl shadow hover:shadow-lg transition">
                            <h4 class="text-xl font-semibold text-amber-600">Handono Rakhmadi</h4>
                            <p class="text-gray-600 mb-2">Founder</p>
                            <p>
                                Lahir pada Juni 1986, lulusan <span class="font-medium">S1 Teknik Kimia - Institut Teknologi
                                    Sepuluh Nopember (ITS)</span> Surabaya.
                                Berpengalaman di industri pupuk dan riset teknologi pertanian presisi, Handono membawa
                                pendekatan ilmiah dan inovatif dalam setiap aspek budidaya.
                            </p>
                        </div>

                        <!-- Co-Founder -->
                        <div class="bg-gray-50 p-6 rounded-xl shadow hover:shadow-lg transition">
                            <h4 class="text-xl font-semibold text-amber-600">Bagus Darnan Satriawan</h4>
                            <p class="text-gray-600 mb-2">Co-Founder</p>
                            <p>
                                Lahir pada Agustus 1982, menempuh pendidikan <span class="font-medium">S1 dan S2 Ilmu Tanah
                                    - Universitas Brawijaya</span>.
                                Dengan keahliannya dalam manajemen tanah dan kesuburan lahan, Bagus berperan penting dalam
                                pengembangan praktik budidaya melon yang efisien dan ramah lingkungan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Penutup -->
                <div class="text-center mt-16">
                    <p class="text-gray-700 text-lg max-w-2xl mx-auto italic">
                        “Kami percaya, dari desa kecil di Malang inilah, kami dapat menanam harapan besar bagi masa depan
                        pertanian Indonesia.”
                    </p>
                    <p class="text-amber-600 font-semibold mt-4">
                        Selamat datang di <span class="text-green-700">Pagelaran Bertani</span> — tempat di mana rasa, ilmu,
                        dan kerja keras tumbuh menjadi kesejahteraan bersama.
                    </p>
                </div>
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
