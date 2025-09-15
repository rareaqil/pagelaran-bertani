@extends('frontend.layouts.main')

@section('content')
    <div class="bg-white py-16">
        <div class="max-w-4xl mx-auto px-6 md:px-12">
            <!-- Judul -->
            <h2 class="text-3xl md:text-4xl font-bold text-center text-green-600 mb-6">
                Hubungi Kami
            </h2>
            <p class="text-center text-gray-600 mb-12">
                Jika Anda memiliki pertanyaan, ingin bekerja sama, atau membutuhkan informasi lebih lanjut mengenai
                produk kami, silakan hubungi kami melalui form berikut atau melalui kontak yang tersedia.
            </p>

            <!-- Form Kontak -->
            <form action="#" method="POST" class="bg-amber-50 shadow-lg rounded-xl p-8 space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-amber-500 mb-2">Email</label>
                    <input type="email" id="email" name="email"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none"
                        placeholder="Alamat email Anda" required>
                </div>

                <!-- WhatsApp -->
                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-amber-500 mb-2">WhatsApp</label>
                    <input type="text" id="whatsapp" name="whatsapp"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none"
                        placeholder="Nomor WhatsApp aktif" required>
                </div>

                <!-- Instagram -->
                <div>
                    <label for="instagram" class="block text-sm font-medium text-amber-500 mb-2">Instagram</label>
                    <input type="text" id="instagram" name="instagram"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none"
                        placeholder="@username Anda" required>
                </div>

                <!-- Pesan -->
                <div>
                    <label for="message" class="block text-sm font-medium text-amber-500 mb-2">Pesan</label>
                    <textarea id="message" name="message" rows="5"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none"
                        placeholder="Tulis pesan Anda di sini..." required></textarea>
                </div>

                <!-- Tombol -->
                <div class="text-center">
                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg shadow hover:bg-green-700 transition">
                        Kirim Pesan
                    </button>
                </div>
            </form>

            <!-- Info Kontak + Modal -->
            <div x-data="{ open: false, targetUrl: '' }" class="mt-12 text-center">
                <p class="text-gray-600">Atau hubungi langsung melalui:</p>

                <!-- WhatsApp -->
                <p>
                    <span class="font-semibold text-green-600">WhatsApp:</span>
                    <a href="https://wa.me/6281234567890"
                        @click.prevent="targetUrl = 'https://wa.me/6281234567890'; open = true"
                        class="text-amber-600 underline hover:text-amber-700">
                        +62 812-3456-7890
                    </a>
                </p>

                <!-- Instagram -->
                <p>
                    <span class="font-semibold text-green-600">Instagram:</span>
                    <a href="https://instagram.com/pagelaranbertani"
                        @click.prevent="targetUrl = 'https://instagram.com/pagelaranbertani'; open = true"
                        class="text-amber-600 underline hover:text-amber-700">
                        @pagelaranbertani
                    </a>
                </p>

                <!-- Modal Konfirmasi -->
                <div x-show="open" x-cloak @keydown.escape.window="open = false" @click.self="open = false"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <!-- Box Modal -->
                    <div class="bg-white p-6 rounded-2xl shadow-2xl max-w-md w-full mx-4 relative transform transition"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-90 opacity-0"
                        x-transition:enter-end="scale-100 opacity-100" x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-90 opacity-0">
                        <!-- Tombol Close -->
                        <button @click="open = false"
                            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition">
                            ✖
                        </button>

                        <h3 class="text-lg font-semibold mb-4 text-amber-500">Konfirmasi</h3>
                        <p class="mb-4 text-gray-700">Kamu yakin akan menuju alamat ini?</p>
                        <p class="mb-6 text-sm text-gray-500 break-all" x-text="targetUrl"></p>

                        <div class="flex justify-center space-x-4">
                            <button @click="open = false"
                                class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 transition">
                                Batal
                            </button>
                            <button @click="window.location.href = targetUrl"
                                class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                                Ya, Lanjutkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
@endsection
