<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Master Voucher</h2>
    </x-slot>

    <div class="rounded-xl bg-white p-6 shadow-md" x-data="{ openModal: false }" @close-modal.window="openModal=false"
        @open-modal.window="openModal=true">
        <div class="mb-4 flex items-center justify-between">
            <x-primary-button @click="openModal=true; resetForm();">+ Tambah Voucher</x-primary-button>
        </div>

        {{-- === Table === --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-gray-700" id="voucher-table">
                <thead class="bg-gray-100 uppercase tracking-wide text-gray-800 hidden md:table-header-group">
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
                        <tr data-id="{{ $v->id }}"
                            class="hover:bg-gray-50 md:table-row block border rounded-md mb-3 p-3 md:p-0 md:border-0">

                            {{-- Code --}}
                            <td class="code px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Code: </span>{{ $v->code }}
                            </td>

                            {{-- Type --}}
                            <td class="type px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Type: </span>{{ $v->type }}
                            </td>

                            {{-- Value --}}
                            <td class="value px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Value: </span>
                                @if ($v->type === 'percentage')
                                    {{ $v->value }}%
                                @else
                                    Rp {{ number_format($v->value, 0, ',', '.') }}
                                @endif
                            </td>

                            {{-- Min Order --}}
                            <td class="min_order_amount px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Min Order: </span>
                                Rp {{ number_format($v->min_order_amount, 0, ',', '.') }}
                            </td>

                            {{-- Max Usage --}}
                            <td class="max_usage px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Max Usage:
                                </span>{{ $v->max_usage }}
                            </td>

                            {{-- Used --}}
                            <td class="used_count px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Used: </span>{{ $v->used_count }}
                            </td>

                            {{-- Start Date --}}
                            <td class="start_date px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Start: </span>{{ $v->start_date }}
                            </td>

                            {{-- End Date --}}
                            <td class="end_date px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">End: </span>{{ $v->end_date }}
                            </td>

                            {{-- Status --}}
                            <td class="status px-3 py-2 text-center md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Status: </span>
                                <span
                                    class="{{ $v->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }} inline-block rounded-full px-2 py-0.5 text-xs font-semibold">
                                    {{ $v->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="space-x-2 px-3 py-2 text-center md:table-cell block mt-2 md:mt-0">
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
        <div x-show="openModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
                <h3 class="mb-4 text-lg font-semibold" id="modal-title">Tambah Voucher</h3>

                <form id="voucher-form" class="space-y-3" x-data="{ type: 'percentage' }">
                    @csrf
                    <input type="hidden" id="voucher-id" />

                    {{-- Code --}}
                    <div>
                        <x-input-label required for="code" value="Kode Voucher" />
                        <x-text-input id="code" name="code" type="text" class="w-full mt-1"
                            placeholder="Kode Voucher" />
                        <x-input-error :messages="$errors->get('code')" class="mt-1" />
                    </div>

                    {{-- Type --}}
                    <div>
                        <x-input-label for="type" value="Tipe Voucher" />
                        <select id="type" name="type" class="w-full rounded-md border-gray-300 mt-1"
                            x-on:change="type = $event.target.value">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-1" />
                    </div>

                    {{-- Value --}}
                    <div class="relative mt-3">
                        <x-input-label required for="value" value="Nilai" />
                        <div class="relative mt-1">

                            {{-- PREFIX Rp --}}
                            <div x-show="type === 'fixed'" x-cloak
                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-sm font-medium text-gray-600">
                                Rp
                            </div>

                            {{-- INPUT --}}
                            <x-text-input id="value" name="value" x-bind:max="type === 'percentage' ? 100 : null"
                                x-bind:step="1" x-bind:min="0" type="number"
                                class="w-full pl-10 pr-10" placeholder="Masukkan nilai" />

                            {{-- SUFFIX % --}}
                            <div x-show="type === 'percentage'" x-cloak
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-sm font-medium text-gray-600">
                                %
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('value')" class="mt-1" />
                    </div>

                    {{-- Min Order Amount --}}
                    <div>
                        <x-input-label for="min_order_amount" value="Minimal Order" />
                        <x-text-input id="min_order_amount" name="min_order_amount" type="number" min="0"
                            step="0" class="w-full mt-1" placeholder="Min Order" />
                        <x-input-error :messages="$errors->get('min_order_amount')" class="mt-1" />
                    </div>

                    {{-- Max Usage --}}
                    <div>
                        <x-input-label for="max_usage" value="Maksimal Penggunaan" />
                        <x-text-input id="max_usage" name="max_usage" min="0" type="number"
                            class="w-full mt-1" placeholder="Max Usage" />
                        <x-input-error :messages="$errors->get('max_usage')" class="mt-1" />
                    </div>

                    {{-- Start Date --}}
                    <div>
                        <x-input-label required for="start_date" value="Tanggal Mulai" />
                        <input id="start_date" name="start_date" type="datetime-local"
                            class="mt-1 w-full rounded-md border-gray-300" />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-1" />
                    </div>

                    {{-- End Date --}}
                    <div>
                        <x-input-label for="end_date" value="Tanggal Berakhir" />
                        <input id="end_date" name="end_date" type="datetime-local"
                            class="mt-1 w-full rounded-md border-gray-300" />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-1" />
                    </div>


                    {{-- Active --}}
                    <div class="flex items-center justify-between">
                        <x-input-label for="is_active" value="Aktif" class="mb-0" />

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_active" name="is_active" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 transition">
                            </div>
                            <div
                                class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-full">
                            </div>

                        </label>
                    </div>



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

        function cleanNumber(str) {
            if (!str) return '';
            return str.replace(/[^\d]/g, ''); // hapus semua selain angka
        }

        // Fungsi format datetime-local
        function formatDateTimeLocal(str) {
            if (!str) return '';
            str = str.trim().replace(' ', 'T'); // ubah ' ' jadi 'T'
            const d = new Date(str);
            if (isNaN(d)) return '';
            return d.toISOString().slice(0, 16);
        }

        function newRow(v) {
            const activeClass = v.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700';
            const activeText = v.is_active ? 'Aktif' : 'Nonaktif';
            const toggleText = v.is_active ? 'Nonaktifkan' : 'Aktifkan';

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
                <span class="badge ${activeClass}">${activeText}</span>
            </td>
            <td class="px-2 py-1 text-center">
                <button class="edit text-blue-600 hover:underline">Edit</button>
                <button class="toggle ml-2 text-indigo-600 hover:underline">${toggleText}</button>
                <button class="delete ml-2 text-red-600 hover:underline">Hapus</button>
            </td>
        </tr>`;
        };

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
            const badge = tr.find('.status span:not(.md\\:hidden)'); // target badge, bukan label mobile
            const btn = tr.find('.toggle');

            if (active) {
                badge.text('Aktif')
                    .attr('class',
                        'bg-green-100 text-green-700 inline-block rounded-full px-2 py-0.5 text-xs font-semibold');
                btn.text('Nonaktifkan');
            } else {
                badge.text('Nonaktif')
                    .attr('class', 'bg-gray-200 text-gray-700 inline-block rounded-full px-2 py-0.5 text-xs font-semibold');
                btn.text('Aktifkan');
            }
        }



        // Create / Update
        $('#voucher-form').on('submit', function(e) {
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
                id: id || null,
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
                success: function(res) {
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
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            $(`#${field}`).addClass('border-red-500');
                            $(`#${field}`).next('.input-error-message').remove(); // hapus duplikasi
                            $(`#${field}`).after(
                                `<p class="text-red-500 text-xs input-error-message">${errors[field][0]}</p>`
                            );
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan',
                        });
                    }
                },
            });
        });

        $(document).on('click', '.edit', function() {
            const tr = $(this).closest('tr');
            const id = tr.data('id');

            $.get(`/backend/vouchers/${id}`, function(res) {
                const v = res.data;

                $('#voucher-id').val(v.id);
                $('#code').val(v.code);

                $('#type').val(v.type).trigger('change'); // agar Alpine update prefix/suffix
                $('#value').val(v.value);
                $('#min_order_amount').val(v.min_order_amount ?? '');
                $('#max_usage').val(v.max_usage ?? '');
                $('#start_date').val(formatDateTimeLocal(v.start_date));
                $('#end_date').val(formatDateTimeLocal(v.end_date));
                $('#is_active').prop('checked', v.is_active);

                $('#save-btn').text('Update');
                $('#modal-title').text('Edit Voucher');
                window.dispatchEvent(new CustomEvent('open-modal'));
            });
        });


        // Toggle Active
        $(document).on('click', '.toggle', function() {
            const tr = $(this).closest('tr');
            $.post(`/backend/vouchers/${tr.data('id')}/toggle`, {
                    _token: token
                })
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
        $(document).on('click', '.delete', function() {
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
                        data: {
                            _token: token
                        },
                        success: () => {
                            tr.remove();
                            Swal.fire('Terhapus!', 'Voucher berhasil dihapus.', 'success');
                        },
                        error: (err) => {
                            Swal.fire('Gagal!', err.responseJSON?.message ?? 'Gagal menghapus',
                                'error');
                        },
                    });
                }
            });
        });
    </script>
</x-app-layout>
