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

        {{-- User Details --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input
                    id="first_name"
                    name="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('first_name', $user->first_name)"
                    required
                />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input
                    id="last_name"
                    name="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('last_name', $user->last_name)"
                    required
                />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    class="mt-1 block w-full"
                    :value="old('email', $user->email)"
                    required
                />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
            <div>
                <x-input-label for="phone" :value="__('Phone')" />
                <x-text-input
                    id="phone"
                    name="phone"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('phone', $user->phone)"
                />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
        </div>

        {{-- Address --}}
        <div class="grid grid-cols-1 gap-4">
            <div>
                <x-input-label for="address1" :value="__('Address Line 1')" />
                <x-text-input
                    id="address1"
                    name="address1"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('address1', $user->address1)"
                />
                <x-input-error class="mt-2" :messages="$errors->get('address1')" />
            </div>
            {{--
                <div>
                <x-input-label for="address2" :value="__('Address Line 2')" />
                <x-text-input
                id="address2"
                name="address2"
                type="text"
                class="mt-1 block w-full"
                :value="old('address2', $user->address2)"
                />
                <x-input-error class="mt-2" :messages="$errors->get('address2')" />
                </div>
            --}}
        </div>

        {{-- Select2 Cascading --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <x-input-label for="province_id" :value="__('Province')" />
                <select id="province_id" name="province_id" class="form-control js-select2"></select>
                <x-input-error class="mt-2" :messages="$errors->get('province_id')" />
            </div>
            <div>
                <x-input-label for="city_id" :value="__('City')" />
                <select id="city_id" name="city_id" class="form-control js-select2"></select>
                <x-input-error class="mt-2" :messages="$errors->get('city_id')" />
            </div>
            <div>
                <x-input-label for="district_id" :value="__('District')" />
                <select id="district_id" name="district_id" class="form-control js-select2"></select>
                <x-input-error class="mt-2" :messages="$errors->get('district_id')" />
            </div>
            <div>
                <x-input-label for="village_id" :value="__('Village')" />
                <select id="village_id" name="village_id" class="form-control js-select2"></select>
                <x-input-error class="mt-2" :messages="$errors->get('village_id')" />
            </div>
        </div>

        <div class="mt-4 flex items-center gap-4">
            <x-primary-button>{{ __('Save All') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => (show = false), 2000)"
                    class="text-sm text-gray-600"
                >
                    Saved.
                </p>
            @endif
        </div>
    </form>

    @push('scripts')
        <script type="module">
            $(function () {
                function initSelect2(selector, placeholder, ajaxUrl = null) {
                    let config = {
                        placeholder: placeholder,
                        allowClear: true,
                        width: '100%',
                    };
                    if (ajaxUrl) {
                        config.ajax = {
                            url: ajaxUrl,
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                return { results: data.map((item) => ({ id: item.id, text: item.name })) };
                            },
                        };
                    }
                    $(selector).select2(config);
                }

                // --- Fetch list dulu baru prefill ---
                function fetchAndPrefill(selector, ajaxUrl, id, text, placeholder, callback = null) {
                    initSelect2(selector, placeholder, ajaxUrl);

                    if (id && text) {
                        // pastikan value user ada di list, kalau tidak append saja
                        $.get(ajaxUrl, function (data) {
                            let exists = data.some((item) => item.id == id);
                            if (!exists) {
                                let option = new Option(text, id, true, true);
                                $(selector).append(option);
                            } else {
                                let option = new Option(text, id, true, true);
                                $(selector).append(option);
                            }
                            $(selector).val(id).trigger('change');

                            if (callback) callback();
                        });
                    } else {
                        if (callback) callback();
                    }
                }

                // --- Prefill berurutan dengan fetch list dulu ---
                fetchAndPrefill(
                    '#province_id',
                    '{{ url('/api/provinces') }}',
                    '{{ $user->province_id }}',
                    '{{ $user->province_name ?? '' }}',
                    'Select Province',
                    function () {
                        fetchAndPrefill(
                            '#city_id',
                            '{{ $user->province_id ? url('/api/cities/' . $user->province_id) : '#' }}',
                            '{{ $user->city_id }}',
                            '{{ $user->city_name ?? '' }}',
                            'Select City',
                            function () {
                                fetchAndPrefill(
                                    '#district_id',
                                    '{{ $user->city_id ? url('/api/districts/' . $user->city_id) : '#' }}',
                                    '{{ $user->district_id }}',
                                    '{{ $user->district_name ?? '' }}',
                                    'Select District',
                                    function () {
                                        fetchAndPrefill(
                                            '#village_id',
                                            '{{ $user->district_id ? url('/api/villages/' . $user->district_id) : '#' }}',
                                            '{{ $user->village_id }}',
                                            '{{ $user->village_name ?? '' }}',
                                            'Select Village',
                                        );
                                    },
                                );
                            },
                        );
                    },
                );

                // --- Cascading Change Events ---
                $('#province_id').on('change', function () {
                    let pid = $(this).val();
                    $('#city_id,#district_id,#village_id').val(null).trigger('change');
                    if (!pid) return initSelect2('#city_id', 'Select City', '{{ url('/api/cities') }}/' + pid);
                });

                $('#city_id').on('change', function () {
                    let cid = $(this).val();
                    $('#district_id,#village_id').val(null).trigger('change');
                    if (!cid)
                        return initSelect2('#district_id', 'Select District', '{{ url('/api/districts') }}/' + cid);
                });

                $('#district_id').on('change', function () {
                    let did = $(this).val();
                    $('#village_id').val(null).trigger('change');
                    if (!did) return initSelect2('#village_id', 'Select Village', '{{ url('/api/villages') }}/' + did);
                });
            });
        </script>
    @endpush
</section>
