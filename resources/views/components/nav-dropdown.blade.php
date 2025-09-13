@props([
    'align' => 'right',
    'width' => '48',
    'top' => '36',
    'active' => false,   {{-- untuk highlight menu aktif --}}
    'contentClasses' => 'py-1 bg-white',
])

@php
    // posisi dan lebar menu dropdown
    $alignmentClasses = match ($align) {
        'left' => 'start-0 ltr:origin-top-left rtl:origin-top-right',
        'top' => 'origin-top',
        default => 'end-0 ltr:origin-top-right rtl:origin-top-left',
    };
    $width = match ($width) {
        '48' => 'w-48',
        default => $width,
    };

    $marginTop = match (true) {
        $top === '2' => 'mt-2', // default
        default => "mt-{$top}", // misal mt="36" -> mt-36
    };

    // class sama persis dengan x-nav-link
    $navBase = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5
                                            focus:outline-none transition duration-150 ease-in-out';
    $navActive = 'border-indigo-400 text-gray-900 focus:border-indigo-700';
    $navInactive = 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700';
@endphp

{{-- ✅ class nav-link sekarang ditempel di <div> pembungkus --}}
<div
    class="{{ $navBase }} {{ $active ? $navActive : $navInactive }} relative"
    x-data="{ open: false }"
    @click.outside="open = false"
    @close.stop="open = false"
>
    {{-- Trigger hanya jadi tombol polos --}}
    <button type="button" @click="open = ! open" class="flex items-center focus:outline-none">
        {{ $trigger }}
        <svg class="ms-1 h-4 w-4 fill-current" viewBox="0 0 20 20">
            <path
                fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1
                     0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd"
            />
        </svg>
    </button>

    {{-- Isi dropdown --}}
    <div
        x-show="open"
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="scale-95 opacity-0"
        x-transition:enter-end="scale-100 opacity-100"
        x-transition:leave="transition duration-75 ease-in"
        x-transition:leave-start="scale-100 opacity-100"
        x-transition:leave-end="scale-95 opacity-0"
        class="{{ $width }} {{ $alignmentClasses }} {{ $marginTop }} absolute z-50 rounded-md shadow-lg"
        style="display: none"
        @click="open = false"
    >
        <div class="{{ $contentClasses }} rounded-md ring-1 ring-black ring-opacity-5">
            {{ $content }}
        </div>
    </div>
</div>
