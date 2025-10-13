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

        {{-- === Table === --}}
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

                            {{-- Index/Icon --}}
                            <td class="px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">#</span>
                                {{ $loop->iteration }}
                            </td>

                            {{-- User --}}
                            <td class="px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">User: </span>
                                {{ $t->user->name ?? '—' }}
                            </td>

                            {{-- Product --}}
                            <td class="px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Product: </span>
                                <span class="inline-block bg-gray-100 text-gray-800 text-xs rounded-full px-2 py-0.5">
                                    {{ $t->product->name ?? '—' }}
                                </span>
                            </td>

                            {{-- Rating --}}
                            <td class="px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Rating: </span>
                                <div class="flex items-center">
                                    @php $full = (int) $t->rating; @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $full)
                                            <span class="text-yellow-500 mr-0.5">★</span>
                                        @else
                                            <span class="text-gray-300 mr-0.5">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </td>

                            {{-- Comment (short) --}}
                            <td class="px-3 py-2 md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Komentar: </span>
                                <div class="text-sm text-gray-700" title="{{ $t->comment }}">
                                    {{ \Illuminate\Support\Str::limit($t->comment, 60, '...') }}
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-3 py-2 text-center md:table-cell block">
                                <span class="md:hidden font-semibold text-gray-600">Status: </span>
                                <span
                                    class="{{ $t->is_approved ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }} inline-block rounded-full px-2 py-0.5 text-xs font-semibold status-badge">
                                    {{ $t->is_approved ? 'Published' : 'Hidden' }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="space-x-2 px-3 py-2 text-center md:table-cell block mt-2 md:mt-0">
                                <button class="edit text-blue-600 hover:underline">Edit</button>
                                <button class="toggle text-indigo-600 hover:underline">
                                    {{ $t->is_approved ? 'Hide' : 'Publish' }}
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
                <h3 class="mb-4 text-lg font-semibold" id="modal-title">Tambah Testimonial</h3>

                <form id="testimonial-form" class="space-y-3" x-data="{ rating: 5 }">
                    @csrf
                    <input type="hidden" id="testimonial-id" />

                    {{-- User --}}
                    <div>
                        <x-input-label required for="user_id" value="User" />
                        <select id="user_id" name="user_id" class="w-full rounded-md border-gray-300 mt-1">
                            <option value="">-- Pilih User --</option>
                            @foreach ($users as $u)
                                {{-- assume controller already filtered role === 'user' --}}
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
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
                                {{-- assume controller already filtered status_active = true --}}
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('product_id')" class="mt-1" />
                    </div>

                    {{-- Rating --}}
                    <div>
                        <x-input-label required for="rating" value="Rating" />
                        <div class="mt-1">
                            <select id="rating" name="rating" class="w-full rounded-md border-gray-300">
                                <option value="">-- Pilih Rating --</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} ★</option>
                                @endfor
                            </select>
                        </div>
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
                        <x-primary-button id="save-btn">Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Alpine & jQuery --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- SweetAlert2 (jika layout belum include) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const token = '{{ csrf_token() }}';

        function resetForm() {
            $('#testimonial-id').val('');
            $('#testimonial-form')[0].reset();
            $('#is_approved').prop('checked', false);
            $('#save-btn').text('Simpan');
            $('#modal-title').text('Tambah Testimonial');
        }

        function shortComment(str, len = 60) {
            if (!str) return '';
            return str.length > len ? str.substring(0, len - 3) + '...' : str;
        }

        function buildStars(n) {
            let out = '';
            for (let i = 1; i <= 5; i++) {
                out += i <= n ? '<span class="text-yellow-500">★</span>' : '<span class="text-gray-300">★</span>';
            }
            return out;
        }

        function escapeHtml(text) {
            if (text == null) return '';
            return $('<div>').text(text).html();
        }

        function newRow(t) {
            const stars = buildStars(t.rating);
            return `<tr data-id="${t.id}" class="hover:bg-gray-50 md:table-row block border rounded-md mb-3 p-3 md:p-0 md:border-0">
                <td class="px-3 py-2 md:table-cell block">${t.id}</td>
                <td class="px-3 py-2 md:table-cell block">${escapeHtml(t.user_name)}</td>
                <td class="px-3 py-2 md:table-cell block"><span class="inline-block bg-gray-100 text-gray-800 text-xs rounded-full px-2 py-0.5">${escapeHtml(t.product_name)}</span></td>
                <td class="px-3 py-2 md:table-cell block">${stars}</td>
                <td class="px-3 py-2 md:table-cell block">${escapeHtml(shortComment(t.comment))}</td>
                <td class="px-3 py-2 md:table-cell block text-center"><span class="${t.is_approved ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700'} inline-block rounded-full px-2 py-0.5 text-xs font-semibold status-badge">${t.is_approved ? 'Published' : 'Hidden'}</span></td>
                <td class="px-3 py-2 md:table-cell block text-center">
                    <button class="edit text-blue-600 hover:underline">Edit</button>
                    <button class="toggle ml-2 text-indigo-600 hover:underline">${t.is_approved ? 'Hide' : 'Publish'}</button>
                    <button class="delete ml-2 text-red-600 hover:underline">Hapus</button>
                </td>
            </tr>`;
        }

        // Create / Update via AJAX
        $('#testimonial-form').on('submit', function(e) {
            e.preventDefault();
            const id = $('#testimonial-id').val();
            const payload = {
                _token: token,
                id: id,
                user_id: $('#user_id').val(),
                product_id: $('#product_id').val(),
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
                        // replace existing row
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
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $('.input-error-message').remove();
                        for (const field in errors) {
                            $(`#${field}`).addClass('border-red-500');
                            $(`#${field}`).after(
                                `<p class="text-red-500 text-xs input-error-message">${errors[field][0]}</p>`
                                );
                        }
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                    }
                },
            });
        });

        // Edit: populate modal from server to get full comment (recommended)
        $(document).on('click', '.edit', function() {
            const tr = $(this).closest('tr');
            const id = tr.data('id');

            // Fetch detail from server (add route GET /testimonials/{id} if not present)
            $.get(`/backend/testimonials/${id}`, {
                    _token: token
                })
                .done(function(res) {
                    const t = res.data ?? res;
                    $('#testimonial-id').val(t.id);
                    $('#user_id').val(t.user_id);
                    $('#product_id').val(t.product_id);
                    $('#rating').val(t.rating);
                    $('#comment').val(t.comment);
                    $('#is_approved').prop('checked', t.is_approved ? true : false);

                    $('#save-btn').text('Update');
                    $('#modal-title').text('Edit Testimonial');
                    window.dispatchEvent(new CustomEvent('open-modal'));
                })
                .fail(function() {
                    // fallback: try to parse from row (less reliable)
                    $('#testimonial-id').val(id);
                    const userText = tr.find('td:nth-child(2)').text().trim();
                    $('#user_id option').filter(function() {
                        return $(this).text().trim() === userText;
                    }).prop('selected', true);
                    const productText = tr.find('td:nth-child(3) span').text().trim();
                    $('#product_id option').filter(function() {
                        return $(this).text().trim() === productText;
                    }).prop('selected', true);
                    const ratingCount = tr.find('td:nth-child(4) .text-yellow-500').length;
                    $('#rating').val(ratingCount || 5);
                    const fullComment = tr.find('td:nth-child(5)').attr('title') || '';
                    $('#comment').val(fullComment);
                    const statusText = tr.find('.status-badge').text().trim();
                    $('#is_approved').prop('checked', statusText === 'Published');

                    $('#save-btn').text('Update');
                    $('#modal-title').text('Edit Testimonial');
                    window.dispatchEvent(new CustomEvent('open-modal'));
                });
        });

        // Toggle Publish/Hide
        $(document).on('click', '.toggle', function() {
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            $.post(`/backend/testimonials/${id}/toggle`, {
                    _token: token
                })
                .done((res) => {
                    const badge = tr.find('.status-badge');
                    const btn = tr.find('.toggle');
                    if (res.status) {
                        badge.text('Published').attr('class',
                            'bg-green-100 text-green-700 inline-block rounded-full px-2 py-0.5 text-xs font-semibold status-badge'
                            );
                        btn.text('Hide');
                    } else {
                        badge.text('Hidden').attr('class',
                            'bg-gray-200 text-gray-700 inline-block rounded-full px-2 py-0.5 text-xs font-semibold status-badge'
                            );
                        btn.text('Publish');
                    }
                    Swal.fire('Berhasil', res.message || 'Status diperbarui', 'success');
                })
                .fail((err) => {
                    Swal.fire('Gagal', err.responseJSON?.message ?? 'Gagal ubah status', 'error');
                });
        });

        // Delete
        $(document).on('click', '.delete', function() {
            const tr = $(this).closest('tr');
            const id = tr.data('id');

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: 'Testimonial ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/backend/testimonials/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: token
                        },
                        success: () => {
                            tr.remove();
                            Swal.fire('Terhapus!', 'Testimonial berhasil dihapus.', 'success');
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
