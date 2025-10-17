@php
    use App\Models\OrderStatus;
@endphp

<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">Order #{{ $order->order_id }}</h2>
</x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded bg-red-100 p-4 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
            {{-- Order Info --}}
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <p>
                        <strong>Order ID:</strong>
                        {{ $order->order_id }}
                    </p>
                    <p>
                        <strong>Tanggal:</strong>
                        {{ $order->created_at->format('d-m-Y H:i') }}
                    </p>
                    <p>
                        <strong>Status:</strong>
                        {{ $order->status }}
                    </p>
                </div>
                <div>
                    <p>
                        <strong>Nama Pemesan:</strong>
                        {{ $order->user->first_name ?? '-' }}
                    </p>
                    <p>
                        <strong>Email:</strong>
                        {{ $order->user->email ?? '-' }}
                    </p>
                    <p>
                        <strong>No Telp:</strong>
                        {{ $order->user->phone ?? '-' }}
                    </p>
                    <p>
                        <strong>Alamat:</strong>
                        {{ $order->user->primaryAddress->address1 ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Produk</th>
                            <th class="border px-4 py-2 text-center">Qty</th>
                            <th class="border px-4 py-2 text-right">Harga</th>
                            <th class="border px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="border px-4 py-2">{{ $item->product->name ?? $item->name }}</td>
                                <td class="border px-4 py-2 text-center">{{ $item->quantity }}</td>
                                <td class="border px-4 py-2 text-right">
                                    {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="border px-4 py-2 text-right">
                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold">
                            <td colspan="3" class="border px-4 py-2 text-right">Subtotal</td>
                            <td class="border px-4 py-2 text-right">
                                {{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                        </tr>

                        @if ($order->voucher)
                            <tr class="text-green-700">
                                <td colspan="3" class="border px-4 py-2 text-right">
                                    Discount (Voucher: {{ $order->voucher->code }})
                                    @if ($order->voucher->type === 'percentage')
                                        ({{ $order->voucher->value }}%)
                                    @endif
                                </td>
                                <td class="border px-4 py-2 text-right">
                                    -{{ number_format($discountAmount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif

                        {{-- Tambahkan Admin Fee --}}
                        <tr class="text-blue-700">
                            <td colspan="3" class="border px-4 py-2 text-right">Biaya Admin</td>
                            <td class="border px-4 py-2 text-right">
                                {{ number_format($adminFee ?? 2000, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr class="font-bold">
                            <td colspan="3" class="border px-4 py-2 text-right">Total</td>
                            <td class="border px-4 py-2 text-right">
                                {{ number_format($total + ($adminFee ?? 2000), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- After Paid --}}
            {{-- if status_payment = success && status = paid --}}
            {{-- untuk User | Add Penjelasan untuk mengirimkan chat ke whatsaap Admin berupa detail order dan lain lain dalam bentuk button menggunaan wa.me --}}
            {{-- untuk Admin | Add field untuk mengisi seperti estimasi pengiriman, link untuk lacak, kemudian ??? , jika sudah isi ubah status ke shipment --}}

            {{-- @if ($order->status_payment === 'success' && $order->status->value === 'paid') --}}
            @if ($order->status->value === OrderStatus::Paid->value)
                <div class="mt-8 border-t pt-6">
                    {{-- USER: Tombol WhatsApp --}}
                    @php
                        $waUrl = auth()->user()->isAdmin() ? $waUrlUser : $waUrlAdmin;
                    @endphp

                    <div x-data="{ open: false, targetUrl: '{{ $waUrl }}' }">
                        <p class="mb-2 text-sm text-gray-700">
                            @if (auth()->user()->isAdmin())
                                Hubungi User via WhatsApp untuk konfirmasi pesanan.
                            @else
                                Hubungi Admin via WhatsApp untuk konfirmasi pesanan Anda.
                            @endif
                        </p>

                        <!-- Tombol WhatsApp -->
                        <a href="{{ $waUrl }}" @click.prevent="targetUrl = '{{ $waUrl }}'; open = true"
                            class="cursor-pointer rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                            @if (auth()->user()->isAdmin())
                                Chat User via WhatsApp
                            @else
                                Chat Admin via WhatsApp
                            @endif
                        </a>

                        <!-- Modal Konfirmasi -->
                        <div x-show="open" x-cloak @keydown.escape.window="open = false" @click.self="open = false"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition"
                            x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition duration-200 ease-in"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            <!-- Box Modal -->
                            <div class="relative mx-4 w-full max-w-md transform rounded-2xl bg-white p-6 shadow-2xl transition"
                                x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="scale-90 opacity-0"
                                x-transition:enter-end="scale-100 opacity-100"
                                x-transition:leave="transition duration-200 ease-in"
                                x-transition:leave-start="scale-100 opacity-100"
                                x-transition:leave-end="scale-90 opacity-0">
                                <!-- Tombol Close -->
                                <button @click="open = false"
                                    class="absolute right-3 top-3 text-gray-400 transition hover:text-gray-600">
                                    ✖
                                </button>

                                <h3 class="mb-4 text-lg font-semibold text-green-600">Konfirmasi</h3>
                                <p class="mb-4 text-gray-700">Kamu yakin ingin membuka link ini?</p>
                                <p class="mb-6 break-all text-sm text-gray-500" x-text="targetUrl"></p>

                                <div class="flex justify-center space-x-4">
                                    <button @click="open = false"
                                        class="rounded-lg bg-gray-300 px-4 py-2 transition hover:bg-gray-400">
                                        Batal
                                    </button>
                                    <button @click="window.open(targetUrl, '_blank'); open = false"
                                        class="rounded-lg bg-green-600 px-4 py-2 text-white transition hover:bg-green-700">
                                        Ya, Lanjutkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ADMIN: Form input pengiriman lengkap --}}
                    @if (auth()->user()->isAdmin())
                        <form {{-- action="#" --}} action="{{ route('orders.setShipment', $order) }}" method="POST"
                            class="mt-4 space-y-4">
                            @csrf
                            @method('POST')

                            @if ($errors->any())
                                <div class="bg-red-100 p-3 rounded">
                                    <ul class="list-disc pl-5 text-red-600">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            {{-- Tanggal & Waktu Kirim --}}
                            <label class="block">
                                <span class="text-gray-700">Tanggal & Waktu Kirim <span
                                        class="text-red-500">*</span></span>
                                <input type="datetime-local" name="scheduled_at"
                                    class="mt-1 w-full rounded border-gray-300"
                                    value="{{ old('scheduled_at', optional($order->scheduled_at)->format('Y-m-d\TH:i')) }}"
                                    required />
                            </label>

                            {{-- Estimasi Durasi (menit) --}}
                            <label class="block">
                                <span class="text-gray-700">Estimasi Durasi (menit) <span
                                        class="text-red-500">*</span></span>
                                <input type="number" name="estimate_minutes" min="1" step="1"
                                    class="mt-1 w-full rounded border-gray-300"
                                    value="{{ old('estimate_minutes', $order->estimate_minutes) }}"
                                    placeholder="misal: 45" required />
                                <span class="text-sm text-gray-500">Lama perjalanan, contoh 45 untuk ±45 menit.</span>
                            </label>

                            {{-- Link Lacak --}}
                            <label class="block">
                                <span class="text-gray-700">Link Lacak</span>
                                <input type="url" name="tracking_link" class="mt-1 w-full rounded border-gray-300"
                                    value="{{ old('tracking_link', $order->tracking_link) }}"
                                    placeholder="https://kurir.example/track/ABC123" />
                            </label>

                            {{-- Kurir --}}
                            <label class="block">
                                <span class="text-gray-700">Kurir <span class="text-red-500">*</span></span>
                                <select name="courier" class="mt-1 w-full rounded border-gray-300">
                                    <option value="">-- Pilih Kurir --</option>
                                    @php
                                        $couriersSetting = \App\Models\Setting::where(
                                            'key',
                                            'available_couriers',
                                        )->value('value');
                                        $couriers = $couriersSetting
                                            ? array_map('trim', explode(',', $couriersSetting))
                                            : [];
                                    @endphp

                                    @foreach ($couriers as $courier)
                                        <option value="{{ $courier }}"
                                            {{ old('courier', $order->courier) == $courier ? 'selected' : '' }}>
                                            {{ $courier }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>


                            <button type="button" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                                onclick="confirmAction(this.form, 'Apakah kamu yakin ingin menyimpan detail pengiriman ini?')">
                                Set Shipment
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- Shipment --}}
            {{-- if status_payment = success && status = shipment --}}
            {{-- Add Tabel Detail Pengiriman | isinya After Paid yang telah diisi oleh Admin --}}
            {{-- USER: Detail Pengiriman --}}
            {{-- @dd($order->status->value, '', OrderStatus::Pending->value) --}}
            @if ($order->payment?->status === 'PAID' && $order->status->value === OrderStatus::Shipment->value)
                {{-- @if ($order->status->value === 'Paid') --}}
                <div class="mt-8 border-t pt-6">
                    <h3 class="mb-2 text-lg font-semibold">Detail Pengiriman</h3>

                    <table class="mb-4 w-full table-auto border border-gray-200 text-sm">
                        <tr>
                            <th class="border px-4 py-2 text-left">Kurir</th>
                            <td class="border px-4 py-2">{{ $order->courier ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="border px-4 py-2 text-left">Tanggal & Waktu Kirim</th>
                            <td class="border px-4 py-2">
                                {{ $order->scheduled_at ? $order->scheduled_at->format('d M Y H:i') : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="border px-4 py-2 text-left">Estimasi Durasi</th>
                            <td class="border px-4 py-2">
                                @if ($order->estimate_minutes)
                                    ±{{ $order->estimate_minutes }} menit
                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                        {{-- Perkiraan Waktu Tiba --}}
                        <tr>
                            <th class="border px-4 py-2 text-left">Perkiraan Tiba</th>
                            <td class="border px-4 py-2">
                                {{ $order->estimated_arrival ? $order->estimated_arrival->format('d M Y H:i') : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="border px-4 py-2 text-left">Link Lacak</th>
                            <td class="border px-4 py-2">
                                @if ($order->tracking_link)
                                    <a href="{{ $order->tracking_link }}" class="text-blue-600 hover:underline"
                                        target="_blank">
                                        Lacak Pengiriman
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>

                    {{-- Tombol Konfirmasi Pesanan Diterima (hanya untuk user, bukan admin) --}}
                    @if ($order->user_id === auth()->id() || auth()->user()->isAdmin())
                        <form action="{{ route('orders.confirmReceived', $order) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="button"
                                class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                                onclick="confirmAction(this.form, 'Konfirmasi bahwa pesanan telah diterima?')">
                                Konfirmasi Pesanan Diterima
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- Back button --}}

            @php
                $current = url()->current(); // URL lengkap sebelumnya
                $baseCurrent = preg_replace('#/[^/]+$#', '', $current); // hapus segmen terakhir
            @endphp

            <div class="mt-6">
                <a href="{{ $baseCurrent }}" class="rounded bg-gray-300 px-4 py-2 hover:bg-gray-400">Kembali</a>
            </div>
            {{-- Ditutup Sementara, Untuk Konfirmasi Stok --}}

            {{--
                <div class="mt-6">
                @foreach ($holdMovements as $movement)
                <form
                action="{{ route('stock.confirmPayment', $movement->id) }}"
                method="POST"
                style="display: inline"
                >
                @csrf
                <button type="submit" class="btn btn-success">
                Confirm Payment ({{ $movement->quantity }} pcs)
                </button>
                </form>
                @endforeach
                </div>
            --}}

            @if ($order->status->value === OrderStatus::Pending->value)
                <div class="mt-4 flex justify-end space-x-2">
                    {{-- Tombol Batalkan --}}
                    <form action="{{ route('orders.orderReversal', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="button" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700"
                            onclick="confirmAction(this.form, 'Yakin batalkan pesanan ini?')">
                            Batalkan Pesanan
                        </button>
                    </form>

                    {{-- Tombol Lakukan Pembayaran --}}
                    <button id="pay-button" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                        Lakukan Pembayaran
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
<script src="//unpkg.com/alpinejs" defer></script>

@push('scripts')
    <script>
        function confirmAction(form, message) {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, yakin',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    {{-- Midtrans Snap JS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ midtrans_config('client_key') }}">
    </script>
    <script type="module">
        let snapOpen = false;

        const payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function(e) {
            e.preventDefault();

            if (snapOpen) return; // Jangan buka popup jika sudah terbuka

            snapOpen = true;

            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('Success:', result);
                    snapOpen = false;
                    location.reload();
                },
                onPending: function(result) {
                    console.log('Pending:', result);
                    snapOpen = false;
                },
                onError: function(result) {
                    console.log('Error:', result);
                    snapOpen = false;
                },
                onClose: function() {
                    console.log('Popup closed by user');
                    snapOpen = false;
                },
            });
        });
    </script>

    @if ($order->status->value === OrderStatus::Pending->value)
        <script type="module">
            window.addEventListener('load', function() {
                document.getElementById('pay-button').click();
            });
        </script>
    @endif
@endpush
