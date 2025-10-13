{{-- resources/views/backend/testimonials/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Master Testimonial</h2>
    </x-slot>

    <div class="rounded-xl bg-white p-6 shadow-md" x-data="{ openModal: false }" @close-modal.window="openModal=false"
        @open-modal.window="openModal=true">
        <div class="mb-4 flex items-center justify-between">
            <x-primary-button @click="openModal=true; resetForm();">+ Tambah Testimonial</x-primary-button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-gray-700" id="testimonial-table">
                <thead class="bg-gray-100 uppercase tracking-wide text-gray-800 hidden md:table-header-group">
                    <tr>
                        <th class="border px-3 py-2">#</th>
                        <th class="border px-3 py-2">User</th>
                        <th class="border px-3 py-2">Product</th>
                        <th class="border px-3 py-2">Rating</th>
                        <th class="border px-3 py-2">Komentar</th>
                        <th class="border px-3 py-2">Status</th>
                        <th class="w-48 border px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($testimonials as $t)
                        <tr data-id="{{ $t->id }}"
                            class="hover:bg-gray-50 md:table-row block border rounded-md mb-3 p-3 md:p-0 md:border-0">
                            <td class="px-3 py-2 md:table-cell block">{{ $loop->iteration }}</td>
                            <td class="px-3 py-2 md:table-cell block">{{ $t->user->full_name ?? '—' }}</td>
                            <td class="px-3 py-2 md:table-cell block"><span
                                    class="inline-block bg-gray-100 text-gray-800 text-xs rounded-full px-2 py-0.5">{{ $t->product->name ?? '—' }}</span>
                            </td>
                            <td class="px-3 py-2 md:table-cell block">
                                @php $full = (int) $t->rating; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $full ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                @endfor
                            </td>
                            <td class="px-3 py-2 md:table-cell block" title="{{ $t->comment }}">
                                {{ \Illuminate\Support\Str::limit($t->comment, 60, '...') }}</td>
                            <td class="px-3 py-2 md:table-cell block text-center">
                                <span
                                    class="{{ $t->is_approved ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }} inline-block rounded-full px-2 py-0.5 text-xs font-semibold status-badge">
                                    {{ $t->is_approved ? 'Published' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 md:table-cell block text-center">
                                <button class="edit text-blue-600 hover:underline">Edit</button>
                                <button
                                    class="toggle ml-2 text-indigo-600 hover:underline">{{ $t->is_approved ? 'Hide' : 'Publish' }}</button>
                                <button class="delete ml-2 text-red-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Modal --}}
        <div x-show="openModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
                <h3 class="mb-4 text-lg font-semibold" id="modal-title">Tambah Testimonial</h3>

                <form id="testimonial-form" class="space-y-3">
                    @csrf
                    <input type="hidden" id="testimonial-id" />

                    {{-- User --}}
                    <div>
                        <x-input-label required for="user_id" value="User" />
                        <select id="user_id" name="user_id" class="w-full rounded-md border-gray-300 mt-1">
                            <option value="">-- Pilih User --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->full_name }} ({{ $u->email }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                    </div>

                    {{-- Product --}}
                    <div>
                        <x-input-label required for="product_id" value="Product" />
                        <select id="product_id" name="product_id" class="w-full rounded-md border-gray-300 mt-1">
                            <option value="">-- Pilih Product --</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('product_id')" class="mt-1" />
                    </div>

                    {{-- Rating --}}
                    <div>
                        <x-input-label required for="rating" value="Rating" />
                        <select id="rating" name="rating" class="w-full rounded-md border-gray-300 mt-1">
                            <option value="">-- Pilih Rating --</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} ★</option>
                            @endfor
                        </select>
                        <x-input-error :messages="$errors->get('rating')" class="mt-1" />
                    </div>

                    {{-- Comment --}}
                    <div>
                        <x-input-label for="comment" value="Komentar" />
                        <textarea id="comment" name="comment" rows="4" class="w-full mt-1 rounded-md border-gray-300"
                            placeholder="Tulis komentar..."></textarea>
                        <x-input-error :messages="$errors->get('comment')" class="mt-1" />
                    </div>

                    {{-- Published --}}
                    <div class="flex items-center justify-between">
                        <x-input-label for="is_approved" value="Published" class="mb-0" />
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_approved" name="is_approved" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 transition">
                            </div>
                            <div
                                class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-full">
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <x-secondary-button type="button" @click="openModal=false">Batal</x-secondary-button>
                        <x-primary-button id="save-btn" type="submit">Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Tom Select --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <script>
        const token = '{{ csrf_token() }}';

        // Init Tom Select
        const userSelect = new TomSelect("#user_id", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
        const productSelect = new TomSelect("#product_id", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        function resetForm() {
            $('#testimonial-id').val('');
            $('#testimonial-form')[0].reset();
            $('#is_approved').prop('checked', false);
            userSelect.clear();
            productSelect.clear();
            $('#save-btn').text('Simpan');
            $('#modal-title').text('Tambah Testimonial');
            $('.input-error-message').remove();
        }

        function buildStars(n) {
            let out = '';
            for (let i = 1; i <= 5; i++) {
                out += i <= n ? '<span class="text-yellow-500">★</span>' : '<span class="text-gray-300">★</span>';
            }
            return out;
        }

        function newRow(t) {
            const stars = buildStars(t.rating);
            return `<tr data-id="${t.id}" class="hover:bg-gray-50 md:table-row block border rounded-md mb-3 p-3 md:p-0 md:border-0">
                <td class="px-3 py-2 md:table-cell block">${t.id}</td>
                <td class="px-3 py-2 md:table-cell block">${t.user_name}</td>
                <td class="px-3 py-2 md:table-cell block"><span class="inline-block bg-gray-100 text-gray-800 text-xs rounded-full px-2 py-0.5">${t.product_name}</span></td>
                <td class="px-3 py-2 md:table-cell block">${stars}</td>
                <td class="px-3 py-2 md:table-cell block" title="${t.comment}">${t.comment.length > 60 ? t.comment.substring(0,57)+'...' : t.comment}</td>
                <td class="px-3 py-2 md:table-cell block text-center"><span class="${t.is_approved ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700'} inline-block rounded-full px-2 py-0.5 text-xs font-semibold status-badge">${t.is_approved ? 'Published' : 'Hidden'}</span></td>
                <td class="px-3 py-2 md:table-cell block text-center">
                    <button class="edit text-blue-600 hover:underline">Edit</button>
                    <button class="toggle ml-2 text-indigo-600 hover:underline">${t.is_approved ? 'Hide' : 'Publish'}</button>
                    <button class="delete ml-2 text-red-600 hover:underline">Hapus</button>
                </td>
            </tr>`;
        }

        // Submit form (Create / Update)
        $('#testimonial-form').on('submit', function(e) {
            e.preventDefault();
            const id = $('#testimonial-id').val();
            const payload = {
                _token: token,
                id: id,
                user_id: userSelect.getValue(),
                product_id: productSelect.getValue(),
                rating: $('#rating').val(),
                comment: $('#comment').val(),
                is_approved: $('#is_approved').is(':checked') ? 1 : 0,
            };

            $.ajax({
                url: id ? `/backend/testimonials/${id}` : '{{ route('testimonials.store') }}',
                type: id ? 'PUT' : 'POST',
                data: payload,
                dataType: 'json',
                success: function(res) {
                    const t = res.data ?? res;
                    if (id) {
                        const row = $(`#testimonial-table tr[data-id="${id}"]`);
                        row.replaceWith(newRow(t));
                    } else {
                        $('#testimonial-table tbody').prepend(newRow(t));
                    }
                    resetForm();
                    window.dispatchEvent(new CustomEvent('close-modal'));
                    Swal.fire('Berhasil', res.message || 'Sukses', 'success');
                },
                error: function(xhr) {
                    $('.input-error-message').remove();
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            const el = $(`#${field}`);
                            el.after(
                                `<div class="text-red-600 text-sm input-error-message">${errors[field][0]}</div>`
                            );
                        }
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan!', 'error');
                    }
                }
            });
        });

        // Edit
        $(document).on('click', '.edit', function() {
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            $.get(`/backend/testimonials/${id}`, {
                    _token: token
                })
                .done(function(res) {
                    const t = res.data ?? res;
                    $('#testimonial-id').val(t.id);
                    $('#rating').val(t.rating);
                    $('#comment').val(t.comment);
                    $('#is_approved').prop('checked', t.is_approved ? true : false);
                    userSelect.setValue(t.user_id);
                    productSelect.setValue(t.product_id);
                    $('#save-btn').text('Update');
                    $('#modal-title').text('Edit Testimonial');
                    window.dispatchEvent(new CustomEvent('open-modal'));
                });
        });

        // Toggle Publish / Hide
        $(document).on('click', '.toggle', function() {
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            $.post(`/backend/testimonials/${id}/toggle`, {
                    _token: token
                })
                .done(function(res) {
                    const badge = tr.find('.status-badge');
                    badge.text(res.status ? 'Published' : 'Hidden');
                    badge.toggleClass('bg-green-100 text-green-700 bg-gray-200 text-gray-700');
                    $(tr).find('.toggle').text(res.status ? 'Hide' : 'Publish');
                    Swal.fire('Berhasil', res.message, 'success');
                });
        });

        // Delete
        $(document).on('click', '.delete', function() {
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            Swal.fire({
                title: 'Hapus Testimonial?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/backend/testimonials/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: token
                        },
                        success: function(res) {
                            tr.remove();
                            Swal.fire('Berhasil', res.message, 'success');
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
