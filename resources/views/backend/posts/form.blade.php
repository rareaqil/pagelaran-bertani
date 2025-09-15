<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ isset($post) ? 'Edit Post' : 'Tambah Post' }}
        </h2>
    </x-slot>

    <div class="mt-6 space-y-6 rounded-lg bg-white p-4 shadow sm:rounded-xl sm:p-8">
        <div class="mx-auto max-w-3xl space-y-6 rounded-xl bg-white p-6 shadow">
            <form
                action="{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf
                @if (isset($post))
                    @method('PUT')
                @endif

                {{-- Judul --}}
                <div>
                    <x-input-label for="name" :value="'Judul'" />
                    <x-text-input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ old('name', $post->name ?? '') }}"
                        required
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                {{-- Intro --}}
                <div>
                    <x-input-label for="intro" :value="'Intro'" />
                    <x-text-input
                        id="intro"
                        name="intro"
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ old('intro', $post->intro ?? '') }}"
                        placeholder="Masukkan intro singkat..."
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('intro')" />
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    {{-- Type --}}
                    <div>
                        <x-input-label for="type" :value="'Type'" />
                        <select id="type" name="type" class="mt-1 block w-full rounded border-gray-300">
                            @php
                                $types = ['artikel', 'berita', 'tutorial'];
                            @endphp

                            @foreach ($types as $t)
                                <option value="{{ $t }}" {{ old('type', $post->type ?? '') == $t ? 'selected' : '' }}>
                                    {{ ucfirst($t) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('type')" />
                    </div>

                    {{-- Status --}}
                    <div>
                        <x-input-label for="status" :value="'Status'" />
                        <select id="status" name="status" class="mt-1 block w-full rounded border-gray-300">
                            @foreach (['draft', 'published', 'archived'] as $status)
                                <option
                                    value="{{ $status }}"
                                    {{ old('status', $post->status ?? '') == $status ? 'selected' : '' }}
                                >
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>
                </div>

                {{-- Konten --}}
                <div>
                    <x-input-label for="content" :value="'Konten'" />
                    <textarea
                        id="content"
                        name="content"
                        class="form-control mt-1 block w-full"
                        placeholder="Masukkan konten..."
                        required
                    >
                        {{ old('content', $post->content ?? '') }}</textarea
                    >
                    <x-input-error class="mt-2" :messages="$errors->get('content')" />
                </div>

                {{-- Upload Gambar Header --}}
                <div>
                    <x-input-label for="image" :value="'Gambar Header'" />
                    <div class="mt-1 flex flex-col gap-4 md:flex-row md:items-center">
                        <div class="flex-1">
                            <div class="flex gap-2">
                                <input
                                    id="image"
                                    name="image"
                                    type="text"
                                    class="flex-1 rounded border-gray-300 p-2"
                                    placeholder="Pilih gambar..."
                                    value="{{ old('image', $post->image ?? '') }}"
                                />
                                <button
                                    class="btn btn-outline-info rounded border border-gray-300 px-4 py-2 text-gray-700"
                                    id="button-image"
                                    type="button"
                                    data-input="image"
                                >
                                    Browse
                                </button>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div class="flex-shrink-0">
                            <img
                                id="preview-image"
                                src="{{ old('image', $post->image ?? asset('images/no-image.png')) }}"
                                alt="Preview"
                                class="rounded border border-gray-300"
                                style="max-height: 180px; width: auto; object-fit: contain; background-color: #f3f3f3"
                            />
                        </div>
                    </div>
                </div>

                <div>
                    <x-primary-button>{{ isset($post) ? 'Update' : 'Simpan' }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css"
            rel="stylesheet"
        />
        <style>
            .note-editor.note-frame :after {
                display: none;
            }
            .note-editor .note-toolbar .note-dropdown-menu,
            .note-popover .popover-content .note-dropdown-menu {
                min-width: 180px;
            }
            img {
                display: inline-block !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script
            type="module"
            src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"
        ></script>
        <script type="module">
            var lfm = function (options, cb) {
                var route_prefix = options && options.prefix ? options.prefix : '/laravel-filemanager';
                window.open(route_prefix + '?type=' + (options.type || 'file'), 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };
            var LFMButton = function (context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="note-icon-picture"></i> ',
                    tooltip: 'Insert image with filemanager',
                    click: function () {
                        lfm({ type: 'image', prefix: '/laravel-filemanager' }, function (lfmItems) {
                            lfmItems.forEach(function (lfmItem) {
                                context.invoke('insertImage', lfmItem.url);
                                $image.addClass('mx-auto d-block');
                            });
                        });
                    },
                });
                return button.render();
            };
            $('#content').summernote({
                height: 480,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['fontname', 'fontsize', 'bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'lfm', 'video']],
                    ['view', ['codeview', 'undo', 'redo', 'help']],
                ],
                buttons: { lfm: LFMButton },
            });
        </script>
        <script type="module" src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
        <script type="module">
            $('#button-image').filemanager('image');
            // Update preview saat path berubah
            $('#image').on('change', function () {
                const url = $(this).val();
                if (url) {
                    $('#preview-image').attr('src', url);
                } else {
                    $('#preview-image').attr('src', '');
                }
            });
        </script>
    @endpush
</x-app-layout>
