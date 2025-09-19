<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen Produk</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Flash message --}}
            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tombol Tambah Produk --}}
            <div class="mb-4 flex items-center justify-between p-4">
                <a
                    href="{{ route('products.create') }}"
                    class="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                >
                    Tambah Produk
                </a>
            </div>

            {{-- Table produk --}}
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <x-table-flexible
                    :data="$products"
                    :columns="[
                        'image'         => 'Gambar',
                        'name'          => 'Nama Produk',
                        'price'         => 'Harga',
                        'stock'         => 'Stok',
                        'status_active' => 'Status',
                        'sku'           => 'SKU',
                        'description'   => 'Deskripsi',
                        'created_at'    => 'Dibuat',
                    ]"
                    :actions="[
                        'detail' => true,
                        'edit'   => 'products.edit',
                        'show'   => 'products.show',
                        'delete' => 'products.destroy',
                        'addStock' => 'stock.add'

                    ]"
                    :maxVisibleColumns="5"
                />
            </div>
        </div>
    </div>
</x-app-layout>
