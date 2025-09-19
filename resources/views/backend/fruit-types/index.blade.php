<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Master Jenis Buah</h2>
    </x-slot>

    {{-- Alpine state + listener event open/close modal --}}
    <div
        class="rounded bg-white p-6 shadow"
        x-data="{ openModal: false, editingId: null }"
        @close-modal.window="openModal = false"
        @open-modal.window="openModal = true"
    >
        {{-- Tombol tambah --}}
        <x-primary-button @click="openModal = true; editingId = null; resetForm();">
            + Tambah Jenis Buah
        </x-primary-button>

        {{-- === Table === --}}
        <table class="mt-4 min-w-full border border-gray-200" id="fruit-table">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Slug</th>
                    <th class="border px-4 py-2 text-center">Status</th>
                    <th class="w-40 border px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fruits as $fruit)
                    <tr data-id="{{ $fruit->id }}">
                        <td class="name px-4 py-2">{{ $fruit->name }}</td>
                        <td class="slug px-4 py-2">{{ $fruit->slug }}</td>
                        <td class="status px-4 py-2 text-center">
                            <span
                                class="badge {{ $fruit->is_active ? 'bg-green-200 text-green-700' : 'bg-gray-200 text-gray-700' }}"
                            >
                                {{ $fruit->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button class="edit text-blue-600 hover:underline">Edit</button>
                            <button class="toggle ml-2 text-indigo-600 hover:underline">
                                {{ $fruit->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                            <button class="delete ml-2 text-red-600 hover:underline">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- === Modal Form === --}}
        <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-md rounded bg-white p-6 shadow-lg">
                <h3 class="mb-4 text-lg font-semibold" id="modal-title">Tambah Jenis Buah</h3>

                <form id="fruit-form">
                    @csrf
                    <input type="hidden" id="fruit-id" />
                    <x-text-input
                        id="fruit-name"
                        type="text"
                        name="name"
                        placeholder="Nama jenis buah"
                        class="mb-4 w-full"
                        required
                    />
                    <div class="flex justify-end gap-2">
                        <x-secondary-button type="button" @click="openModal = false">Batal</x-secondary-button>
                        <x-primary-button id="save-btn">Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Alpine & jQuery --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        const token = '{{ csrf_token() }}';

        function resetForm() {
            $('#fruit-id').val('');
            $('#fruit-name').val('');
            $('#save-btn').text('Simpan');
            $('#modal-title').text('Tambah Jenis Buah');
        }

        // Simpan (create / update)
        $('#fruit-form').on('submit', function (e) {
            e.preventDefault();
            const id = $('#fruit-id').val();
            const name = $('#fruit-name').val();

            $.post('/backend/fruit-types/store', { _token: token, id, name })
                .done(function (res) {
                    if (id) {
                        const row = $(`#fruit-table tr[data-id="${id}"]`);
                        row.find('.name').text(res.name);
                        row.find('.slug').text(res.slug);
                    } else {
                        $('#fruit-table tbody').prepend(`
                            <tr data-id="${res.id}">
                                <td class="name px-4 py-2">${res.name}</td>
                                <td class="slug px-4 py-2">${res.slug}</td>
                                <td class="status px-4 py-2 text-center">
                                    <span class="badge bg-green-200 text-green-700">Aktif</span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button class="edit text-blue-600 hover:underline">Edit</button>
                                    <button class="toggle ml-2 text-indigo-600 hover:underline">Nonaktifkan</button>
                                    <button class="delete ml-2 text-red-600 hover:underline">Hapus</button>
                                </td>
                            </tr>
                        `);
                    }
                    resetForm();
                    window.dispatchEvent(new CustomEvent('close-modal'));
                })
                .fail((err) => alert(err.responseJSON?.message ?? 'Gagal menyimpan'));
        });

        // Edit
        $(document).on('click', '.edit', function () {
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            const name = tr.find('.name').text();

            $('#fruit-id').val(id);
            $('#fruit-name').val(name).focus();
            $('#save-btn').text('Update');
            $('#modal-title').text('Edit Jenis Buah');

            // kirim event agar Alpine membuka modal
            window.dispatchEvent(new CustomEvent('open-modal'));
        });

        // Toggle aktif / nonaktif
        $(document).on('click', '.toggle', function () {
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            $.post(`/backend/fruit-types/${id}/toggle`, { _token: token })
                .done(function (res) {
                    const badge = tr.find('.status span');
                    const btn = tr.find('.toggle');
                    if (res.status) {
                        badge.text('Aktif').removeClass().addClass('badge bg-green-200 text-green-700');
                        btn.text('Nonaktifkan');
                    } else {
                        badge.text('Nonaktif').removeClass().addClass('badge bg-gray-200 text-gray-700');
                        btn.text('Aktifkan');
                    }
                })
                .fail((err) => alert(err.responseJSON?.message ?? 'Gagal mengubah status'));
        });

        // Delete
        $(document).on('click', '.delete', function () {
            if (!confirm('Hapus jenis buah ini?')) return;
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            $.ajax({
                url: `/backend/fruit-types/${id}`,
                type: 'DELETE',
                data: { _token: token },
            })
                .done(() => tr.remove())
                .fail((err) => alert(err.responseJSON?.message ?? 'Gagal menghapus'));
        });
    </script>
</x-app-layout>
