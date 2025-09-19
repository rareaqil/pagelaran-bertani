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

            {{-- Tombol tambah order (opsional, tergantung alur bisnis) --}}
            <div class="mb-4 flex items-center justify-between p-4">
                <a
                    {{-- href="{{ route('admin.orders.create') }}" --}}
                    class="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                >
                    Tambah Order
                </a>
            </div>

            {{-- Tabel Order --}}
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <x-table-flexible
                    :data="$orders"
                    :columns="[
                        'order_id'       => 'Order ID',
                        'user.first_name'      => 'Customer',
                        'total_amount'   => 'Total',
                        'discount_amount'=> 'Diskon',
                        'status'         => 'Status',
                        'expires_at'     => 'Kedaluwarsa',
                        'created_at'     => 'Dibuat',
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
