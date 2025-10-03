<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Master Voucher</h2>
    </x-slot>

    <div
        class="rounded-xl bg-white p-6 shadow-md"
        x-data="{ openModal: false }"
        @close-modal.window="openModal=false"
        @open-modal.window="openModal=true"
    >
        <div class="mb-4 flex items-center justify-between">
            <x-primary-button @click="openModal=true; resetForm();">+ Tambah Voucher</x-primary-button>
        </div>

        {{-- === Table === --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-gray-700" id="voucher-table">
                <thead class="bg-gray-100 uppercase tracking-wide text-gray-800">
                    <tr>
                        <th class="border px-3 py-2">Code</th>
                        <th class="border px-3 py-2">Type</th>
                        <th class="border px-3 py-2">Value</th>
                        <th class="border px-3 py-2">Min Order</th>
                        <th class="border px-3 py-2">Max Usage</th>
                        <th class="border px-3 py-2">Used</th>
                        <th class="border px-3 py-2">Start</th>
                        <th class="border px-3 py-2">End</th>
                        <th class="border px-3 py-2">Active</th>
                        <th class="w-48 border px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($vouchers as $v)
                        <tr data-id="{{ $v->id }}" class="hover:bg-gray-50">
                            <td class="code px-3 py-2">{{ $v->code }}</td>
                            <td class="type px-3 py-2">{{ $v->type }}</td>
                            <td class="value px-3 py-2">{{ $v->value }}</td>
                            <td class="min_order_amount px-3 py-2">{{ $v->min_order_amount }}</td>
                            <td class="max_usage px-3 py-2">{{ $v->max_usage }}</td>
                            <td class="used_count px-3 py-2">{{ $v->used_count }}</td>
                            <td class="start_date px-3 py-2">{{ $v->start_date }}</td>
                            <td class="end_date px-3 py-2">{{ $v->end_date }}</td>
                            <td class="status px-3 py-2 text-center">
                                <span
                                    class="{{ $v->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }} inline-block rounded-full px-2 py-0.5 text-xs font-semibold"
                                >
                                    {{ $v->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="space-x-2 px-3 py-2 text-center">
                                <button class="edit text-blue-600 hover:underline">Edit</button>
                                <button class="toggle text-indigo-600 hover:underline">
                                    {{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                                <button class="delete text-red-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- === Modal === --}}
        <div
            x-show="openModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
                <h3 class="mb-4 text-lg font-semibold" id="modal-title">Tambah Voucher</h3>

                <form id="voucher-form" class="space-y-3">
                    @csrf
                    <input type="hidden" id="voucher-id" />

                    <x-text-input
                        id="code"
                        name="code"
                        type="text"
                        placeholder="Kode Voucher"
                        class="w-full"
                        required
                    />

                    <select id="type" name="type" class="w-full rounded-md border-gray-300">
                        <option value="percentage">Percentage</option>
                        <option value="fixed">Fixed</option>
                    </select>

                    <x-text-input
                        id="value"
                        name="value"
                        type="number"
                        step="0.01"
                        placeholder="Value"
                        class="w-full"
                        required
                    />

                    <x-text-input
                        id="min_order_amount"
                        name="min_order_amount"
                        type="number"
                        step="0.01"
                        placeholder="Min Order"
                        class="w-full"
                    />

                    <x-text-input
                        id="max_usage"
                        name="max_usage"
                        type="number"
                        placeholder="Max Usage"
                        class="w-full"
                    />

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input id="start_date" type="datetime-local" class="mt-1 w-full rounded-md border-gray-300" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input id="end_date" type="datetime-local" class="mt-1 w-full rounded-md border-gray-300" />
                    </div>

                    <label class="inline-flex items-center space-x-2">
                        <input
                            id="is_active"
                            type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            checked
                        />
                        <span>Aktif</span>
                    </label>

                    <div class="flex justify-end space-x-2 pt-2">
                        <x-secondary-button type="button" @click="openModal=false">Batal</x-secondary-button>
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
            $('#voucher-id').val('');
            $('#voucher-form')[0].reset();
            $('#is_active').prop('checked', true);
            $('#save-btn').text('Simpan');
            $('#modal-title').text('Tambah Voucher');
        }

        function formatDateTimeLocal(str) {
            if (!str) return '';
            const d = new Date(str);
            d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
            return d.toISOString().slice(0, 16);
        }

        function newRow(v) {
            return `<tr data-id="${v.id}">
                <td class="code px-2 py-1">${v.code}</td>
                <td class="type px-2 py-1">${v.type}</td>
                <td class="value px-2 py-1">${v.value}</td>
                <td class="min_order_amount px-2 py-1">${v.min_order_amount ?? ''}</td>
                <td class="max_usage px-2 py-1">${v.max_usage ?? ''}</td>
                <td class="used_count px-2 py-1">${v.used_count ?? 0}</td>
                <td class="start_date px-2 py-1">${v.start_date ?? ''}</td>
                <td class="end_date px-2 py-1">${v.end_date ?? ''}</td>
                <td class="status px-2 py-1 text-center">
                    <span class="badge ${v.is_active ? 'bg-green-200 text-green-700' : 'bg-gray-200 text-gray-700'}">
                        ${v.is_active ? 'Aktif' : 'Nonaktif'}
                    </span>
                </td>
                <td class="px-2 py-1 text-center">
                    <button class="edit text-blue-600 hover:underline">Edit</button>
                    <button class="toggle ml-2 text-indigo-600 hover:underline">
                        ${v.is_active ? 'Nonaktifkan' : 'Aktifkan'}
                    </button>
                    <button class="delete ml-2 text-red-600 hover:underline">Hapus</button>
                </td>
            </tr>`;
        }

        function fillRow(row, v) {
            row.find('.code').text(v.code);
            row.find('.type').text(v.type);
            row.find('.value').text(v.value);
            row.find('.min_order_amount').text(v.min_order_amount ?? '');
            row.find('.max_usage').text(v.max_usage ?? '');
            row.find('.used_count').text(v.used_count ?? 0);
            row.find('.start_date').text(v.start_date ?? '');
            row.find('.end_date').text(v.end_date ?? '');
            updateStatus(row, v.is_active);
        }

        function updateStatus(tr, active) {
            const badge = tr.find('.status span');
            const btn = tr.find('.toggle');
            if (active) {
                badge.text('Aktif').attr('class', 'badge bg-green-200 text-green-700');
                btn.text('Nonaktifkan');
            } else {
                badge.text('Nonaktif').attr('class', 'badge bg-gray-200 text-gray-700');
                btn.text('Aktifkan');
            }
        }

        // Create / Update
        $('#voucher-form').on('submit', function (e) {
            e.preventDefault();
            const id = $('#voucher-id').val();
            const start = $('#start_date').val();
            const end = $('#end_date').val();
            if (end && start && end < start) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggal tidak valid',
                    text: 'End Date tidak boleh lebih awal dari Start Date',
                });
                return;
            }

            const payload = {
                _token: token,
                code: $('#code').val(),
                type: $('#type').val(),
                value: $('#value').val(),
                min_order_amount: $('#min_order_amount').val(),
                max_usage: $('#max_usage').val(),
                start_date: start,
                end_date: end,
                is_active: $('#is_active').is(':checked') ? 1 : 0,
            };

            $.ajax({
                url: id ? `/backend/vouchers/${id}` : '{{ route('vouchers.store') }}',
                type: id ? 'PUT' : 'POST',
                data: payload,
                dataType: 'json',
                success: function (res) {
                    const v = res.data ?? res; // antisipasi jika controller lama langsung return object
                    if (id) {
                        fillRow($(`#voucher-table tr[data-id="${id}"]`), v);
                    } else {
                        $('#voucher-table tbody').prepend(newRow(v));
                    }
                    resetForm();
                    window.dispatchEvent(new CustomEvent('close-modal'));
                    Swal.fire('Berhasil', 'Status berhasil diubah', 'success');
                },
                error: function (xhr) {
                    let msg = 'Gagal menyimpan';
                    try {
                        const json = JSON.parse(xhr.responseText);
                        msg = json.message || msg;
                    } catch (e) {
                        msg = xhr.responseText || msg;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: msg,
                    });
                },
            });
        });

        // Edit
        $(document).on('click', '.edit', function () {
            const tr = $(this).closest('tr');
            $('#voucher-id').val(tr.data('id'));
            $('#code').val(tr.find('.code').text());
            $('#type').val(tr.find('.type').text());
            $('#value').val(tr.find('.value').text());
            $('#min_order_amount').val(tr.find('.min_order_amount').text());
            $('#max_usage').val(tr.find('.max_usage').text());
            $('#start_date').val(formatDateTimeLocal(tr.find('.start_date').text()));
            $('#end_date').val(formatDateTimeLocal(tr.find('.end_date').text()));
            $('#is_active').prop('checked', tr.find('.status span').text().trim() === 'Aktif');
            $('#save-btn').text('Update');
            $('#modal-title').text('Edit Voucher');
            window.dispatchEvent(new CustomEvent('open-modal'));
        });

        // Toggle Active
        $(document).on('click', '.toggle', function () {
            const tr = $(this).closest('tr');
            $.post(`/backend/vouchers/${tr.data('id')}/toggle`, { _token: token })
                .done((res) => updateStatus(tr, res.status))
                .fail((err) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Ubah Status',
                        text: err.responseJSON?.message ?? 'Gagal ubah status voucher',
                    });
                });
        });

        // Delete
        $(document).on('click', '.delete', function () {
            const tr = $(this).closest('tr');
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: 'Voucher ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/backend/vouchers/${tr.data('id')}`,
                        type: 'DELETE',
                        data: { _token: token },
                        success: () => {
                            tr.remove();
                            Swal.fire('Terhapus!', 'Voucher berhasil dihapus.', 'success');
                        },
                        error: (err) => {
                            Swal.fire('Gagal!', err.responseJSON?.message ?? 'Gagal menghapus', 'error');
                        },
                    });
                }
            });
        });
    </script>
</x-app-layout>
