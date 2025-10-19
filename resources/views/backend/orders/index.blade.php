<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen Order (Admin)</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Flash message --}}
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

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    {{-- Filter Status --}}
                    <form method="GET" action="{{ route('orders.indexView') }}"
                        class="flex flex-wrap items-center gap-3">
                        {{-- Filter Status --}}
                        <label for="status" class="text-sm">Status</label>
                        <select name="status" class="rounded-md border-gray-300 text-sm">
                            <option value="">-- Semua Status --</option>
                            @foreach (\App\Models\OrderStatus::cases() as $status)
                                <option value="{{ $status->value }}"
                                    {{ request('status') === $status->value ? 'selected' : '' }}>
                                    {{ ucfirst(strtolower($status->value)) }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Filter Tanggal Dari --}}
                        <label for="date_from" class="text-sm">Dari</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="rounded-md border-gray-300 text-sm" />

                        {{-- Filter Tanggal Sampai --}}
                        <label for="date_to" class="text-sm">Sampai</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="rounded-md border-gray-300 text-sm" />

                        <button type="submit"
                            class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white">Terapkan</button>


                        <a href="{{ route('orders.export', request()->only(['status', 'date_from', 'date_to'])) }}"
                            class="px-4 py-2 text-sm rounded-md bg-green-600 text-white hover:bg-green-700">
                            Export Excel
                        </a>
                    </form>


                    {{-- Tombol tambah order --}}
                    <a href="#"
                        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        + Tambah Order
                    </a>
                </div>
            </div>

            {{-- Tabel Order --}}
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <x-table-flexible :data="$orders" :columns="[
                    'order_id' => 'Order ID',
                    'user.first_name' => 'Customer',
                    'created_at' => 'Dibuat',
                    'total_amount' => 'Total',
                    'status' => 'Status',
                    // 'expires_at'     => 'Kedaluwarsa',
                    'discount_amount' => 'Diskon',
                    'updated_at' => 'Diupdate',
                ]" :actions="[
                    // 'edit'   => 'backend.orders.editView',
                    'show' => 'orders.showView',
                    // 'delete' => 'backend.orders.destroy',
                    'detail' => true,
                ]" :maxVisibleColumns="5" />
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Tambahkan script khusus admin bila diperlukan --}}
        <script>
            const dateFrom = document.getElementById('date_from');
            const dateTo = document.getElementById('date_to');

            dateFrom.addEventListener('change', function() {
                if (dateFrom.value) {
                    dateTo.min = dateFrom.value;
                }
            });

            dateTo.addEventListener('change', function() {
                if (dateTo.value) {
                    dateFrom.max = dateTo.value;
                }
            });

            // Init saat halaman reload agar sesuai request old filter
            if (dateFrom.value) {
                dateTo.min = dateFrom.value;
            }
            if (dateTo.value) {
                dateFrom.max = dateTo.value;
            }
        </script>
    @endpush
</x-app-layout>
