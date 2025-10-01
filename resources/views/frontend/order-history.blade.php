@extends('frontend.layouts.main')

@section('content')
    <div class="max-w-6xl mx-auto px-6 md:px-12 py-10 space-y-6">
        <h2 class="text-2xl font-bold text-green-700 mb-6">Riwayat Pesanan</h2>

        {{-- Card Pesanan --}}
        <div class="bg-white shadow rounded-lg border p-6 space-y-4">
            {{-- Header Pesanan --}}
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-500">User ID: <span class="font-medium">#1</span></p>
                <p class="text-sm text-green-600 font-semibold">Status: Dalam Pengiriman</p>
            </div>

            {{-- Detail Transaksi --}}
            <div class="grid grid-cols-2 gap-4 text-sm">
                <p>Total: <span class="font-semibold">Rp 250.000</span></p>
                <p>Voucher: <span class="font-semibold">#VOUCHER123</span></p>
                <p>Diskon: <span class="font-semibold">Rp 25.000</span></p>
                <p>Biaya Admin: <span class="font-semibold">Rp 5.000</span></p>
            </div>

            {{-- Informasi Pengiriman --}}
            <div class="border-t pt-4">
                <h4 class="text-gray-700 font-semibold mb-2">Pengiriman</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <p>Dijadwalkan: <span class="font-semibold">2025-10-02 10:00</span></p>
                    <p>Estimasi (menit): <span class="font-semibold">45</span></p>
                    <p>Kurir: <span class="font-semibold">JNE Express</span></p>
                    <p>Estimasi Tiba: <span class="font-semibold">2025-10-02 11:00</span></p>
                    <p class="col-span-2">Tracking:
                        <a href="https://tracking-link.com" target="_blank" class="text-blue-600 hover:underline">
                            Klik di sini
                        </a>
                    </p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-wrap gap-2 justify-end pt-4 border-t">
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700">
                    Lacak Pesanan
                </button>
                <button class="border border-gray-300 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                    Lihat Detail
                </button>
                <button class="border border-gray-300 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                    Unduh Invoice
                </button>
                <button class="border border-gray-300 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                    Ubah Pesanan
                </button>
            </div>
        </div>
    </div>
@endsection
