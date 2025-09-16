<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen Post</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Flash message --}}
            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tombol Tambah Post --}}
            <div class="mb-4 flex items-center justify-between p-4">
                <a
                    href="{{ route('posts.create') }}"
                    class="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                >
                    Tambah Post
                </a>
            </div>

            {{-- Gunakan komponen table-flexible --}}
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <x-table-flexible
                    :data="$posts"
                    :columns="[
                        'name'                  => 'Judul',
                        'slug'                  => 'Slug',
                        'type'                  => 'Type',
                        'created_by_name'       => 'Penulis',
                        'status'                => 'Status',
                        'published_at'          => 'Tanggal Publikasi',
                        'created_at'            => 'Dibuat',
                        'updated_at'            => 'Diupdate',
                        'updater.first_name'    => 'Last Update Oleh'
                    ]"
                    :actions="[
                        'edit'   => 'posts.edit',
                        'show'   => 'posts.show',
                        'delete' => 'posts.destroy',
                        'detail' => true
                    ]"
                    :maxVisibleColumns="6"
                />
            </div>
        </div>
    </div>

    @push('scripts')

    @endpush
</x-app-layout>
