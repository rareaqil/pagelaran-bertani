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
            <div>
                <x-input-label for="age" :value="__('Age')" />
                <x-text-input
                    id="age"
                    name="age"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('age', $user->age)"
                />
                <x-input-error class="mt-2" :messages="$errors->get('age')" />
            </div>
        </div>

        {{-- ============= ADDRESS ============= --}}
        @php
            $address = $user->primaryAddress; // Bisa null
        @endphp

        <div class="grid grid-cols-1 gap-4">
            <div>
                <x-input-label for="address1" :value="__('Address Line 1')" />
                <x-text-input
                    id="address1"
                    name="address1"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('address1', optional($address)->address1)"
                />
                <x-input-error class="mt-2" :messages="$errors->get('address1')" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <x-input-label for="province_id" :value="__('Province')" />
                <select id="province_id" name="province_id" class="form-control js-select2"></select>
                <x-input-error class="mt-2" :messages="$errors->get('province_id')" />
            </div>
            <div>
                <x-input-label for="regency_id" :value="__('Regency')" />
                <select id="regency_id" name="regency_id" class="form-control js-select2"></select>
                <x-input-error class="mt-2" :messages="$errors->get('regency_id')" />
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

        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <x-input-label for="postcode" :value="__('Postcode')" />
                <x-text-input
                    id="postcode"
                    name="postcode"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('postcode', optional($address)->postcode)"
                />
                <x-input-error class="mt-2" :messages="$errors->get('postcode')" />
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

    {{-- ============= SCRIPTS ============= --}}
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
</section>
