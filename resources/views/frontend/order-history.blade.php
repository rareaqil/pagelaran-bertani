@extends('frontend.layouts.main')

@section('content')
    <div class="max-w-6xl mx-auto px-6 md:px-12 py-10 space-y-6">
        <h2 class="text-2xl font-bold text-green-700 mb-6">Riwayat Pesanan</h2>

        {{-- Filter Status --}}
        <div class="mb-6">
            <form method="GET" action="{{ route('order.history') }}" id="filterForm" class="flex gap-3 items-center">
                <label for="status" class="font-medium text-gray-700">Filter Status:</label>
                <select name="status" id="status" class="border rounded-lg px-3 py-2"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua</option>
                    <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>

        {{-- Loop Pesanan --}}
        @forelse ($orders as $order)
            <div class="bg-white shadow rounded-lg border p-6 space-y-4" x-data="{ open: false }">
                {{-- Header Pesanan --}}
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-500">
                        Order ID: <span class="font-medium">#{{ $order->id }}</span>
                    </p>
                    <p
                        class="text-sm font-semibold
                        @if ($order->status === 'Paid') text-green-600
                        @elseif($order->status === 'Pending') text-yellow-600
                        @elseif($order->status === 'Shipped') text-blue-600
                        @elseif($order->status === 'Cancelled') text-red-600
                        @else text-gray-600 @endif">
                        Status: {{ $order->status }}
                    </p>
                </div>

                {{-- Detail Transaksi --}}
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <p>Total: <span class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </p>
                    <p>Voucher: <span class="font-semibold">{{ $order->voucher_id ?? '-' }}</span></p>
                    <p>Diskon: <span class="font-semibold">Rp
                            {{ number_format($order->discount_amount, 0, ',', '.') }}</span></p>
                    <p>Biaya Admin: <span class="font-semibold">Rp
                            {{ number_format($order->admin_fee, 0, ',', '.') }}</span></p>
                </div>

                {{-- Informasi Pengiriman (Expandable) --}}
                <div class="border-t pt-4">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                        <h4 class="text-gray-700 font-semibold">Informasi Pengiriman</h4>
                        <span x-text="open ? '▲' : '▼'" class="text-gray-500"></span>
                    </button>

                    <div x-show="open" x-collapse class="mt-3 grid grid-cols-2 gap-4 text-sm">
                        <p>Dijadwalkan: <span class="font-semibold">{{ $order->scheduled_at }}</span></p>
                        <p>Estimasi (menit): <span class="font-semibold">{{ $order->estimate_minutes }}</span></p>
                        <p>Kurir: <span class="font-semibold">{{ $order->courier }}</span></p>
                        <p>Estimasi Tiba: <span class="font-semibold">{{ $order->estimated_arrival }}</span></p>
                        <p class="col-span-2">Tracking:
                            @if ($order->tracking_link)
                                <a href="{{ $order->tracking_link }}" target="_blank"
                                    class="text-blue-600 hover:underline">
                                    Klik di sini
                                </a>
                            @else
                                <span class="text-gray-500">Tidak tersedia</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-wrap gap-2 justify-end pt-4 border-t">
                    <a href="{{ route('orders.showView', $order->order_id) }}"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">Belum ada riwayat pesanan.</p>
        @endforelse
    </div>
@endsection
