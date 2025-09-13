{{-- resources/views/backend/posts/form.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ isset($post) ? 'Edit Post' : 'Tambah Post' }}
        </h2>
    </x-slot>

    <div class="mt-6 space-y-6 rounded-lg bg-white p-4 shadow sm:rounded-xl sm:p-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <form
                action="{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                @if (isset($post))
                    @method('PUT')
                @endif

                {{-- Judul --}}
                <div class="mb-4">
                    <x-input-label for="title" :value="'Judul'" />
                    <x-text-input
                        id="title"
                        name="title"
                        type="text"
                        class="w-full"
                        value="{{ old('title', $post->title ?? '') }}"
                        required
                    />
                </div>

                {{-- Konten --}}
                <div class="mb-4">
                    <x-input-label for="content" :value="'Konten'" />
                    <textarea
                        id="content"
                        name="content"
                        class="form-control"
                        placeholder="Masukkan konten..."
                        required
                    >
        {{ old('content', $post->content ?? '') }}</textarea
                    >
                </div>

                {{-- Upload Gambar Header --}}
                <div class="row mb-4">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="image" class="form-label">Gambar Header</label>
                            <div class="input-group">
                                <input
                                    id="image"
                                    name="image"
                                    type="text"
                                    class="form-control"
                                    placeholder="Pilih gambar..."
                                />
                                <button class="btn btn-outline-info" id="button-image" type="button" data-input="image">
                                    <i class="fas fa-folder-open"></i>
                                    Browse
                                </button>
                            </div>
                            <img
                                id="preview-image"
                                src=""
                                alt="Preview"
                                class="img-fluid mt-2 rounded"
                                style="max-height: 180px"
                            />
                        </div>
                    </div>
                </div>
                {{--
                    <input id="image" name="image" type="text" />
                    <button id="button-image" data-input="image" type="button">Browse</button>
                --}}

                <x-primary-button>{{ isset($post) ? 'Update' : 'Simpan' }}</x-primary-button>
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
        </style>
    @endpush

    @push('scripts')
        <script
            type="module"
            src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"
        ></script>
        <script type="module">
            // Define function to open filemanager window
            var lfm = function (options, cb) {
                var route_prefix = options && options.prefix ? options.prefix : '/laravel-filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            // Define LFM summernote button
            var LFMButton = function (context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="note-icon-picture"></i> ',
                    tooltip: 'Insert image with filemanager',
                    click: function () {
                        lfm(
                            {
                                type: 'image',
                                prefix: '/laravel-filemanager',
                            },
                            function (lfmItems, path) {
                                lfmItems.forEach(function (lfmItem) {
                                    context.invoke('insertImage', lfmItem.url);
                                });
                            },
                        );
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
                buttons: {
                    lfm: LFMButton,
                },
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
