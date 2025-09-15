<x-app-layout>
    @push('styles')
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css"
            rel="stylesheet"
        />
        <style>
            /* Hilangkan pseudo-element bawaan Summernote */
            .note-editor.note-frame::after {
                display: none;
            }

            /* Dropdown Summernote agar tidak sempit */
            .note-editor .note-toolbar .note-dropdown-menu,
            .note-popover .popover-content .note-dropdown-menu {
                min-width: 180px;
            }

            /* Konten artikel */
            .prose {
                max-width: none;
                line-height: 1.75;
                color: #1f2937; /* text-gray-800 */
            }

            /* Gambar di dalam konten Summernote agar mengikuti align parent */
            .prose img {
                display: inline-block !important;
                max-width: 100%;
                height: auto;
                border-radius: 0.5rem;
            }

            /* Featured image (header) */
            .featured-image {
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
            }
        </style>
    @endpush

    <div class="">
        <div
            class="mx-auto mt-6 max-w-7xl space-y-6 rounded-lg bg-white p-4 py-6 shadow sm:rounded-xl sm:px-6 sm:py-8 lg:px-8"
        >
            {{-- Gambar utama dengan rasio tetap --}}
            @if ($post->image)
                <div class="featured-image mb-8 aspect-[21/9]">
                    <img src="{{ $post->image }}" alt="{{ $post->name }}" class="h-full w-full object-cover" />
                </div>
            @endif

            {{-- Judul & info --}}
            <h1 class="mb-3 text-4xl font-bold leading-tight text-gray-900">
                {{ $post->name }}
            </h1>

            <div class="mb-4 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                <span>
                    Dipublikasikan:
                    {{ optional($post->published_at)->format('d M Y H:i') }}
                </span>
                <span class="mx-1">•</span>
                <span>{{ $post->author->name ?? 'Admin' }}</span>

                {{-- Badge Type --}}
                @if ($post->type)
                    <span
                        class="ml-2 inline-block rounded-full bg-blue-100 px-3 py-0.5 text-xs font-semibold text-blue-700"
                    >
                        {{ ucfirst($post->type) }}
                    </span>
                @endif
            </div>

            {{-- Intro singkat --}}
            @if ($post->intro)
                <p class="mb-8 text-lg text-gray-700">
                    {{ $post->intro }}
                </p>
            @endif

            {{-- Konten artikel --}}
            <article class="prose lg:prose-xl max-w-none leading-relaxed text-gray-800">
                {!! $post->content !!}
            </article>

            {{-- Tombol kembali & edit --}}
            <div class="mt-10 flex gap-4">
                {{-- Kembali ke daftar --}}
                <a
                    href="{{ route('posts.index') }}"
                    class="inline-block rounded-lg bg-green-600 px-6 py-2 text-white shadow transition hover:bg-green-700"
                >
                    ← Kembali ke Daftar
                </a>

                {{-- Edit post --}}
                <a
                    href="{{ route('posts.edit', $post) }}"
                    class="inline-block rounded-lg bg-blue-600 px-6 py-2 text-white shadow transition hover:bg-blue-700"
                >
                    ✏️ Edit
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
