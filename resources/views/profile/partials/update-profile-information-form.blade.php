<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile & Address Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Update your personal and address information.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- ============= USER DETAILS ============= --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <x-input-label for="first_name" required :value="__('Nama Awal')" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)"
                    required />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>
            <div>
                <x-input-label for="last_name" required :value="__('Nama Terakhir')" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)"
                    required />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <x-input-label for="email" required :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                    required />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
            <div>
                <x-input-label for="phone" :value="__('No Telp')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                    :value="old('phone', $user->phone)" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
            <div>
                <x-input-label for="age" :value="__('Umur')" />
                <x-text-input id="age" name="age" type="text" class="mt-1 block w-full"
                    :value="old('age', $user->age)" />
                <x-input-error class="mt-2" :messages="$errors->get('age')" />
            </div>
        </div>

        {{-- ============= ADDRESS ============= --}}
        @php
            $address = $user->primaryAddress; // Bisa null
        @endphp

        <div class="grid grid-cols-1 gap-4">
            <div>
                <x-input-label for="address1" :value="__('Alamat Rumah (Pengiriman)')" />
                <x-text-input id="address1" name="address1" type="text" class="mt-1 block w-full"
                    :value="old('address1', optional($address)->address1)" />
                <x-input-error class="mt-2" :messages="$errors->get('address1')" />
            </div>
        </div>

        {{-- <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div>
                <x-input-label for="province_id" :value="__('Provinsi')" />
                <select id="province_id" name="province_id"
                    class="js-select2 w-full rounded-md border border-gray-300 bg-white text-sm shadow-sm"></select>
                <x-input-error class="mt-2" :messages="$errors->get('province_id')" />
            </div>

            <div>
                <x-input-label for="regency_id" :value="__('Kab / Kota')" />
                <select id="regency_id" name="regency_id"
                    class="js-select2 w-full rounded-md border border-gray-300 bg-white text-sm shadow-sm"></select>
                <x-input-error class="mt-2" :messages="$errors->get('regency_id')" />
            </div>

            <div>
                <x-input-label for="district_id" :value="__('Kecamatan')" />
                <select id="district_id" name="district_id"
                    class="js-select2 w-full rounded-md border border-gray-300 bg-white text-sm shadow-sm"></select>
                <x-input-error class="mt-2" :messages="$errors->get('district_id')" />
            </div>

            <div>
                <x-input-label for="village_id" :value="__('Kelurahan')" />
                <select id="village_id" name="village_id"
                    class="js-select2 w-full rounded-md border border-gray-300 bg-white text-sm shadow-sm"></select>
                <x-input-error class="mt-2" :messages="$errors->get('village_id')" />
            </div>
        </div> --}}

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="province_id" :value="__('Provinsi')" />
                <select id="province_id" name="province_id"
                    class="js-select2 w-full rounded-md border border-gray-300 bg-white text-sm shadow-sm">
                    <option value=""></option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('province_id')" />
            </div>

            <div>
                <x-input-label for="regency_id" :value="__('Kab / Kota')" />
                <select id="regency_id" name="regency_id"
                    class="js-select2 w-full rounded-md border border-gray-300 bg-white text-sm shadow-sm">
                    <option value=""></option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('regency_id')" />
            </div>

            <div>
                <x-input-label for="district_id" :value="__('Kecamatan')" />
                <select id="district_id" name="district_id"
                    class="js-select2 w-full rounded-md border border-gray-300 bg-white text-sm shadow-sm">
                    <option value=""></option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('district_id')" />
            </div>

            <div>
                <x-input-label for="village_id" :value="__('Kelurahan')" />
                <select id="village_id" name="village_id"
                    class="js-select2 w-full rounded-md border border-gray-300 bg-white text-sm shadow-sm">
                    <option value=""></option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('village_id')" />
            </div>
        </div>


        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <x-input-label for="postcode" :value="__('Kode Pos')" />
                <x-text-input id="postcode" name="postcode" type="text" class="mt-1 block w-full"
                    :value="old('postcode', optional($address)->postcode)" />
                <x-input-error class="mt-2" :messages="$errors->get('postcode')" />
            </div>
        </div>

        <div class="mt-4 flex items-center gap-4">
            <x-primary-button>{{ __('Save All') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => (show = false), 2000)"
                    class="text-sm text-gray-600">
                    Saved.
                </p>
            @endif
        </div>
    </form>

    {{-- ============= SCRIPTS ============= --}}
    {{-- @push('scripts')
        <script >
            $(function() {
                function initSelect2(selector, placeholder, ajaxUrl = null) {
                    let config = {
                        placeholder,
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('body'),
                    };
                    if (ajaxUrl && ajaxUrl !== '#') {
                        config.ajax = {
                            url: ajaxUrl,
                            dataType: 'json',
                            delay: 250,
                            processResults: (data) => ({
                                results: data.map((i) => ({
                                    id: i.id,
                                    text: i.name
                                })),
                            }),
                        };
                    }
                    $(selector).select2(config);
                }

                function fetchAndPrefill(selector, ajaxUrl, id, text, placeholder, next = null) {
                    initSelect2(selector, placeholder, ajaxUrl);
                    if (id && text && ajaxUrl !== '#') {
                        $.get(ajaxUrl, function() {
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
                    function() {
                        fetchAndPrefill(
                            '#regency_id',
                            '{{ optional($address)->province_id ? url('/api/regencies/' . optional($address)->province_id) : '#' }}',
                            '{{ optional($address)->regency_id ?? '' }}',
                            '{{ optional($address)->regency_name ?? '' }}',
                            'Select Regency',
                            function() {
                                fetchAndPrefill(
                                    '#district_id',
                                    '{{ optional($address)->regency_id ? url('/api/districts/' . optional($address)->regency_id) : '#' }}',
                                    '{{ optional($address)->district_id ?? '' }}',
                                    '{{ optional($address)->district_name ?? '' }}',
                                    'Select District',
                                    function() {
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
                $('#province_id').on('change', function() {
                    const pid = $(this).val();
                    $('#regency_id,#district_id,#village_id').val(null).trigger('change');
                    if (pid) initSelect2('#regency_id', 'Select Regency', '{{ url('/api/regencies') }}/' +
                        pid);
                });

                $('#regency_id').on('change', function() {
                    const rid = $(this).val();
                    $('#district_id,#village_id').val(null).trigger('change');
                    if (rid) initSelect2('#district_id', 'Select District', '{{ url('/api/districts') }}/' +
                        rid);
                });

                $('#district_id').on('change', function() {
                    const did = $(this).val();
                    $('#village_id').val(null).trigger('change');
                    if (did) initSelect2('#village_id', 'Select Village', '{{ url('/api/villages') }}/' + did);
                });
            });
        </script>
    @endpush --}}

    @push('scripts')
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {

                const address = {
                    province_id: '{{ optional($address)->province_id }}',
                    province_name: '{{ optional($address)->province_name }}',
                    regency_id: '{{ optional($address)->regency_id }}',
                    regency_name: '{{ optional($address)->regency_name }}',
                    district_id: '{{ optional($address)->district_id }}',
                    district_name: '{{ optional($address)->district_name }}',
                    village_id: '{{ optional($address)->village_id }}',
                    village_name: '{{ optional($address)->village_name }}',
                };

                function createTomSelect(selector, placeholder, loadFn) {
                    return new TomSelect(selector, {
                        valueField: 'id',
                        labelField: 'name',
                        searchField: 'name',
                        placeholder: placeholder,
                        persist: false,
                        load: loadFn,
                    });
                }

                // ===== INIT TOM SELECTS =====
                const province = createTomSelect('#province_id', 'Select Province', function(query, callback) {
                    fetch('{{ url('/api/provinces') }}')
                        .then(res => res.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                });

                const regency = createTomSelect('#regency_id', 'Select Regency', function(provinceId, callback) {
                    if (!provinceId) return callback();
                    fetch('{{ url('/api/regencies') }}/' + provinceId)
                        .then(res => res.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                });

                const district = createTomSelect('#district_id', 'Select District', function(regencyId, callback) {
                    if (!regencyId) return callback();
                    fetch('{{ url('/api/districts') }}/' + regencyId)
                        .then(res => res.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                });

                const village = createTomSelect('#village_id', 'Select Village', function(districtId, callback) {
                    if (!districtId) return callback();
                    fetch('{{ url('/api/villages') }}/' + districtId)
                        .then(res => res.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                });

                // ===== CASCADE =====
                province.on('change', value => {
                    regency.clearOptions();
                    regency.clear(true);
                    district.clearOptions();
                    district.clear(true);
                    village.clearOptions();
                    village.clear(true);
                    if (value) regency.load(value);
                });

                regency.on('change', value => {
                    district.clearOptions();
                    district.clear(true);
                    village.clearOptions();
                    village.clear(true);
                    if (value) district.load(value);
                });

                district.on('change', value => {
                    village.clearOptions();
                    village.clear(true);
                    if (value) village.load(value);
                });

                // ===== PREFILL =====
                // Manual load semua provinsi supaya dropdown muncul
                fetch('{{ url('/api/provinces') }}')
                    .then(res => res.json())
                    .then(data => {
                        province.addOption(data);
                        if (address.province_id) {
                            province.setValue(address.province_id);

                            // Prefill Regencies
                            if (address.regency_id) {
                                regency.addOption({
                                    id: address.regency_id,
                                    name: address.regency_name
                                });
                                regency.setValue(address.regency_id);

                                if (address.district_id) {
                                    district.addOption({
                                        id: address.district_id,
                                        name: address.district_name
                                    });
                                    district.setValue(address.district_id);

                                    if (address.village_id) {
                                        village.addOption({
                                            id: address.village_id,
                                            name: address.village_name
                                        });
                                        village.setValue(address.village_id);
                                    }
                                }
                            }
                        }
                    });

            });
        </script>
    @endpush





</section>
