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
                    <form
                        method="GET"
                        action="{{ route('orders.indexView') }}"
                        class="flex flex-wrap items-center gap-3"
                    >
                        <label for="status" class="text-sm font-medium text-gray-700">Filter Status</label>
                        <select
                            id="status"
                            name="status"
                            class="rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">-- Semua Status --</option>
                            @foreach (\App\Models\OrderStatus::cases() as $status)
                                <option
                                    value="{{ $status->value }}"
                                    {{ request('status') === $status->value ? 'selected' : '' }}
                                >
                                    {{ ucfirst(strtolower($status->value)) }}
                                </option>
                            @endforeach
                        </select>
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-blue-600 shadow ring-2 ring-blue-600 hover:bg-blue-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            Terapkan
                        </button>
                    </form>

                    {{-- Tombol tambah order --}}
                    <a
                        href="#"
                        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        + Tambah Order
                    </a>
                </div>
            </div>

            {{-- Tabel Order --}}
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <x-table-flexible
                    :data="$orders"
                    :columns="[
                        'order_id'       => 'Order ID',
                        'user.first_name'      => 'Customer',
                        'created_at'     => 'Dibuat',
                        'total_amount'   => 'Total',
                        'status'         => 'Status',
                        // 'expires_at'     => 'Kedaluwarsa',
                        'discount_amount'=> 'Diskon',
                        'updated_at'     => 'Diupdate'
                    ]"
                    :actions="[
                        // 'edit'   => 'backend.orders.editView',
                        'show'   => 'orders.showView',
                        // 'delete' => 'backend.orders.destroy',
                        'detail' => true
                    ]"
                    :maxVisibleColumns="5"
                />
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Tambahkan script khusus admin bila diperlukan --}}
    @endpush
</x-app-layout>
