{{-- resources/views/backend/users/form.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ isset($user) ? 'Edit User' : 'Tambah User' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 rounded bg-red-100 p-4 text-red-800">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST">
                @csrf
                @if (isset($user))
                    @method('PUT')
                @endif

                {{-- Nama --}}
                <div class="mb-4">
                    <label class="block text-gray-700">First Name</label>
                    <input
                        type="text"
                        name="first_name"
                        value="{{ old('first_name', $user->first_name ?? '') }}"
                        class="w-full rounded border px-3 py-2"
                    />
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Last Name</label>
                    <input
                        type="text"
                        name="last_name"
                        value="{{ old('last_name', $user->last_name ?? '') }}"
                        class="w-full rounded border px-3 py-2"
                    />
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email ?? '') }}"
                        class="w-full rounded border px-3 py-2"
                    />
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="block text-gray-700">
                        Password
                        @if (isset($user))
                            (isi jika ingin ganti)
                        @endif
                    </label>
                    <input type="password" name="password" class="w-full rounded border px-3 py-2" />
                </div>

                {{-- Role --}}
                <div class="mb-4">
                    <label class="block text-gray-700">Role</label>
                    <select name="role" class="w-full rounded border px-3 py-2">
                        @foreach (['super_admin', 'admin', 'user'] as $role)
                            <option
                                value="{{ $role }}"
                                {{ old('role', $user->role ?? '') == $role ? 'selected' : '' }}
                            >
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Alamat --}}
                <div class="mb-4">
                    <label class="block text-gray-700">Address</label>
                    <input
                        type="text"
                        name="address1"
                        value="{{ old('address1', $user->primaryAddress->address1 ?? '') }}"
                        class="w-full rounded border px-3 py-2"
                    />
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Postcode</label>
                    <input
                        type="text"
                        name="postcode"
                        value="{{ old('postcode', $user->primaryAddress->postcode ?? '') }}"
                        class="w-full rounded border px-3 py-2"
                    />
                </div>

                {{-- Provinsi, Kabupaten, Kecamatan, Desa --}}
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700">Province</label>
                        <select
                            id="province_id"
                            name="province_id"
                            class="form-control js-select2 w-full rounded border px-3 py-2"
                        ></select>
                    </div>
                    <div>
                        <label class="block text-gray-700">Regency</label>
                        <select id="regency_id" name="regency_id" class="w-full rounded border px-3 py-2"></select>
                    </div>
                    <div>
                        <label class="block text-gray-700">District</label>
                        <select id="district_id" name="district_id" class="w-full rounded border px-3 py-2"></select>
                    </div>
                    <div>
                        <label class="block text-gray-700">Village</label>
                        <select id="village_id" name="village_id" class="w-full rounded border px-3 py-2"></select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                        {{ isset($user) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        // Jika $user ada dan punya primaryAddress, ambil nilainya. Kalau tidak, null.
        $address = isset($user) ? optional($user->primaryAddress) : null;
    @endphp

    {{-- ==================== SCRIPT SELECT2 CASCADING ==================== --}}
    @push('scripts')
        <script type="module">
            $(function () {
                function initSelect2(selector, placeholder, ajaxUrl = null) {
                    let config = { placeholder, allowClear: true, width: '100%' };
                    if (ajaxUrl && ajaxUrl !== '#') {
                        config.ajax = {
                            url: ajaxUrl,
                            dataType: 'json',
                            delay: 250,
                            processResults: (data) => ({
                                results: data.map((i) => ({ id: i.id, text: i.name })),
                            }),
                        };
                    }
                    $(selector).select2(config);
                }

                function fetchAndPrefill(selector, ajaxUrl, id, text, placeholder, next = null) {
                    initSelect2(selector, placeholder, ajaxUrl);
                    if (id && text && ajaxUrl !== '#') {
                        $.get(ajaxUrl, function () {
                            const option = new Option(text, id, true, true);
                            $(selector).append(option).trigger('change');
                            if (next) next();
                        });
                    } else if (next) {
                        next();
                    }
                }

                fetchAndPrefill(
                    '#province_id',
                    '{{ url('/api/provinces') }}',
                    '{{ optional($address)->province_id ?? '' }}',
                    '{{ optional($address)->province_name ?? '' }}',
                    'Select Province',
                    function () {
                        fetchAndPrefill(
                            '#regency_id',
                            '{{ optional($address)->province_id ? url('/api/regencies/' . optional($address)->province_id) : '#' }}',
                            '{{ optional($address)->regency_id ?? '' }}',
                            '{{ optional($address)->regency_name ?? '' }}',
                            'Select Regency',
                            function () {
                                fetchAndPrefill(
                                    '#district_id',
                                    '{{ optional($address)->regency_id ? url('/api/districts/' . optional($address)->regency_id) : '#' }}',
                                    '{{ optional($address)->district_id ?? '' }}',
                                    '{{ optional($address)->district_name ?? '' }}',
                                    'Select District',
                                    function () {
                                        fetchAndPrefill(
                                            '#village_id',
                                            '{{ optional($address)->district_id ? url('/api/villages/' . optional($address)->district_id) : '#' }}',
                                            '{{ optional($address)->village_id ?? '' }}',
                                            '{{ optional($address)->village_name ?? '' }}',
                                            'Select Village',
                                        );
                                    },
                                );
                            },
                        );
                    },
                );

                // Cascading select
                $('#province_id').on('change', function () {
                    const pid = $(this).val();
                    $('#regency_id,#district_id,#village_id').val(null).trigger('change');
                    if (pid) initSelect2('#regency_id', 'Select Regency', '{{ url('/api/regencies') }}/' + pid);
                });

                $('#regency_id').on('change', function () {
                    const rid = $(this).val();
                    $('#district_id,#village_id').val(null).trigger('change');
                    if (rid) initSelect2('#district_id', 'Select District', '{{ url('/api/districts') }}/' + rid);
                });

                $('#district_id').on('change', function () {
                    const did = $(this).val();
                    $('#village_id').val(null).trigger('change');
                    if (did) initSelect2('#village_id', 'Select Village', '{{ url('/api/villages') }}/' + did);
                });
            });
        </script>
    @endpush
</x-app-layout>
